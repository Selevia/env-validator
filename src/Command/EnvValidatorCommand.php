<?php


namespace Selevia\Common\Command;


use Selevia\Common\EnvValidator\Response\VarResponse;
use Selevia\Common\EnvValidator\Status\Status;
use Selevia\Common\EnvValidator\Validator;
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

        $response = $this->getValidator()->execute();

        $successResponses = $response->getVarResponseList(Status::STATUS_SUCCESS);
        $warningResponses = $response->getVarResponseList(Status::STATUS_WARNING);
        $errorResponses = $response->getVarResponseList(Status::STATUS_ERROR);

        $this->printTitle(count($successResponses), count($warningResponses), count($errorResponses), $io);
        $this->printMessages($errorResponses, $warningResponses, $io);
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
     * @param VarResponse[] $errorResponses
     * @param VarResponse[] $warningResponses
     * @param SymfonyStyle  $io
     */
    protected function printMessages(array $errorResponses, array $warningResponses, SymfonyStyle $io): void
    {
        foreach ($errorResponses as $errorResponse) {
            $io->writeln(sprintf('<fg=%s>%s</>', 'red', $errorResponse->createMessage()));
        }
        foreach ($warningResponses as $warningResponse) {
            $io->writeln(sprintf('<fg=%s>%s</>', 'yellow', $warningResponse->createMessage()));
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