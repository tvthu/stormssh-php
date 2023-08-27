<?php

namespace tvthu\StormsshPhp\Commands;

use Exception;
use tvthu\StormsshPhp\Services\InputOutput;
use tvthu\StormsshPhp\Services\GetSshSetting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class Search extends Command
{

    /**
     * The name of the command (the part after "bin/demo").
     *
     * @var string
     */
    protected static $defaultName = 'search';

    /**
     * The command description shown when running "php bin/demo list".
     *
     * @var string
     */
    protected static $defaultDescription = 'Search ssh config!';

    private $input;
    private $parsed_result = [];

    private $getSettingService;

    public function __construct(GetSshSetting $getSettingService)
    {
        $this->getSettingService = $getSettingService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'ssh name')
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to search ssh config...');
    }


    /**
     * Execute the command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int 0 if everything went fine, or an exit code.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new InputOutput($input, $output);
        $name = (string) $input->getArgument('name');

        $temp1 = $_SERVER['HOME'] . '/.ssh/config'; // ssh config patch

        $value = $this->getSettingService->fileGetContents($temp1); // get file content

        $this->input = $value;
        $this->parse();

        $result = $this->search($name);

        if (!empty($result)) {

            $print = [];

            foreach ($result as $host) {
                $user = isset($host['Config']['User']) ? $host['Config']['User'] : $host['Config']['user'];
                $hostname = isset($host['Config']['HostName']) ? $host['Config']['HostName'] : $host['Config']['hostname'];
                $port = isset($host['Config']['Port']) ? $host['Config']['Port'] : $host['Config']['port'];

                $print[] = sprintf('%s -> %s@%s:%s', $host['Host'], $user, $hostname, $port);
            }

            $io->listing($print);
        } else {
            $io->error('not found');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function search($name)
    {

        $result = [];

        if (!empty($this->parsed_result)) {
            foreach ($this->parsed_result as $var) {

                if (strpos($var['Host'], $name) !== false) {
                    if (empty($var['Port'])) {
                        $var['Port'] = 22; // auto port 22
                    }
                    $result[] = $var;
                }
            }
        }

        return $result;
    }

    private function parse()
    {
        $host = array();
        $lines = explode("\n", $this->input);

        foreach ($lines as $line) {
            if (empty($line) || empty(trim($line))) continue;

            try {
                [$_, $key, $value] = $this->regexp_match($line);
                if ($key === 'Host') {
                    if (!empty($host)) $this->parsed_result[] = $host;
                    $host = array('Host' => $value, 'Config' => array());
                } else {
                    $host['Config'][$key] = $value;
                }
            } catch (Exception $ex) {
                break;
            }
        }

        $this->parsed_result[] = $host;
    }

    private function regexp_match(string $subject): array
    {
        $SECTION_PATTERN = '/(\w+)(?:\s*=\s*|\s+)(.+)/';

        if (preg_match($SECTION_PATTERN, trim($subject), $matches)) {
            return $matches;
        } else {
            throw new \Exception('Invalid input');
        }
    }
}
