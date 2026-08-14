<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Ai\Agents\CodingAgent;
use App\Models\AgentConversation;
use App\Console\Prompts\FullWidthTextPromptRenderer;
use Laravel\Prompts\Prompt;
use Laravel\Prompts\TextPrompt;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Console\Formatter\OutputFormatter;
use App\ValueObjects\ModelResponse;

use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

class Conversation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'launch';

    private const WIDTH = 74;
    private const MODEL = 'gemma4:31b-cloud';

    public function handle()
    {
        Process::run('clear');
        Prompt::addTheme('full-width', [
            TextPrompt::class => FullWidthTextPromptRenderer::class,
        ]);
        Prompt::theme('full-width');

        $agent = new CodingAgent();
        $this->banner('Welcome into the new Laravel coding agent');

        $user = User::firstOrCreate([
            'email' => 'user@example.com',
        ], [
            'name' => 'Admin',
            'password' => '',
        ]);

        $input = $this->prompt('Describe the task');
        $response = spin(
            fn () => $agent->forUser($user)->prompt($input,
                provider: config('ai.providers.ollama.driver'),
                model: self::MODEL
            ),
            'Thinking…'
        );
        $responseObject = new ModelResponse($response->raw);
        $this->thinking($responseObject->thinking);
        $this->divider();
        $this->answer($responseObject->message);

        while (true) {

            $input = $this->prompt('Prompt');
            if (strtolower($input) === '/exit') {
                $this->newLine();
                $this->line('  <fg=magenta>◇</> <fg=gray>Exiting the conversation. Goodbye!</>');
                $this->newLine();
                break;
            }
            
            do{
                $response = spin(
                    fn () => $agent->continueLastConversation($user)->prompt($input,
                        provider: config('ai.providers.ollama.driver'),
                        model: self::MODEL
                    ),
                    'Thinking…'
                );

                $responseObject = new ModelResponse($response->raw);

                $this->thinking($responseObject->thinking);
                $input = $responseObject->thought;
            }while($responseObject->need_to_reasoning);

            $this->divider();
            $this->answer($responseObject->message);
        }
    }

    /**
     * Draw the opening banner.
     */
    private function banner(string $title): void
    {
        $line = str_repeat('─', self::WIDTH);

        $this->newLine();
        $this->line("  <fg=magenta>╭{$line}╮</>");
        $this->line('  <fg=magenta>│</>'.$this->pad('<fg=cyan;options=bold>◆ '.$title.'</>', mb_strlen($title) + 2).'<fg=magenta>│</>');
        $this->line('  <fg=magenta>│</>'.$this->pad('<fg=gray>type /exit to quit</>', 18).'<fg=magenta>│</>');
        $this->line("  <fg=magenta>╰{$line}╯</>");
        $this->newLine();
    }

    /**
     * Ask the user for input with a styled label.
     */
    private function prompt(string $label): string
    {
        return text(label: $label, placeholder: 'type here…', required: true);
    }

    /**
     * Render the model's reasoning as a dimmed, quoted block.
     */
    private function thinking(?string $text): void
    {
        $this->newLine();
        $this->line('  <fg=gray>╭─ thinking</>');
        foreach ($this->wrap($text) as $row) {
            $this->line('  <fg=gray>│</> <fg=gray>'.OutputFormatter::escape($row).'</>');
        }
        $this->line('  <fg=gray>╰─</>');
    }

    /**
     * Render the model's answer.
     */
    private function answer(?string $text): void
    {
        $this->line('  <fg=green;options=bold>◆ agent</>');
        foreach ($this->wrap($text) as $row) {
            $this->line('    <fg=white>'.OutputFormatter::escape($row).'</>');
        }
        $this->newLine();
    }

    /**
     * Draw a separator between reasoning and answer.
     */
    private function divider(): void
    {
        $this->newLine();
        $this->line('  <fg=magenta>'.str_repeat('─', self::WIDTH + 2).'</>');
        $this->newLine();
    }

    /**
     * Wrap text to the box width.
     *
     * @return array<int, string>
     */
    private function wrap(?string $text): array
    {
        return explode("\n", wordwrap(trim((string) $text), self::WIDTH - 4, "\n", true));
    }

    /**
     * Pad a styled string to the banner width using its visible length.
     */
    private function pad(string $styled, int $visibleLength): string
    {
        return ' '.$styled.str_repeat(' ', max(0, self::WIDTH - $visibleLength - 1));
    }
}
