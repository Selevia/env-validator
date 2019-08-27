<?php


namespace Selevia\Common\EnvValidator\Command;


use Selevia\Common\EnvValidator\Validator\Result\VarResult;
use Selevia\Common\EnvValidator\Validator\Status\Status;
use Selevia\Common\EnvValidator\Validator\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EnvValidatorCommand extends Command
{

    public const COMMAND_NAME = 'env_validator:validate';
    public const COMMAND_DESCRIPTION = 'Validate env variables';
    public const COMMAND_HELP = 'The Validator will load the variables, validate them, and provide a summary of the results';


    /**
     * @var Validator
     */
    protected $validator;

    public function __construct(Validator $validator)
    {
        parent::__construct();

        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);

        $this->setDescription(self::COMMAND_DESCRIPTION);
        $this->setHelp(self::COMMAND_HELP);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->getValidator()->validate();

        $successResults = $result->listVarResults(Status::TYPE_SUCCESS);
        $warningResults = $result->listVarResults(Status::TYPE_WARNING);
        $errorResults = $result->listVarResults(Status::TYPE_ERROR);

        $this->printTitle(count($successResults), count($warningResults), count($errorResults), $io);
        $this->printMessages($errorResults, $warningResults, $io);
    }

    /**
     * @param int          $successCount
     * @param int          $warningCount
     * @param int          $errorCount
     * @param SymfonyStyle $io
     */
    protected function printTitle(int $successCount, int $warningCount, int $errorCount, SymfonyStyle $io): void
    {
        $titleParts = [
            sprintf('<fg=%s>Success: %d</>', 'green', $successCount),
            sprintf('<fg=%s>Warning: %d</>', 'yellow', $warningCount),
            sprintf('<fg=%s>Errors: %d</>', 'red', $errorCount),
        ];

        $io->title(implode(', ', $titleParts));
    }

    /**
     * @param VarResult[]  $errorResults
     * @param VarResult[]  $warningResults
     * @param SymfonyStyle $io
     */
    protected function printMessages(array $errorResults, array $warningResults, SymfonyStyle $io): void
    {
        foreach ($errorResults as $errorResult) {
            $io->writeln(sprintf('<fg=%s>%s</>', 'red', $errorResult->createMessage()));
        }
        foreach ($warningResults as $warningResult) {
            $io->writeln(sprintf('<fg=%s>%s</>', 'yellow', $warningResult->createMessage()));
        }
    }

    /**
     * @return Validator
     */
    protected function getValidator(): Validator
    {
        return $this->validator;
    }
}