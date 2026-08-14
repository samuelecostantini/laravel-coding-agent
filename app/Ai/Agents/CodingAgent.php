<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use App\Ai\Tools\ExecuteBashCommand;
use App\Models\AgentConversation;
use Stringable;

class CodingAgent implements Agent, Conversational, HasTools, HasStructuredOutput
{
    use Promptable, RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 
            'You are an helpful coding agent, you ar running on a Laravel codebase, 
            your db is sqlite, 
            you have many tools but the most important is ExecuteBashCommandTool.'
            .'Put the reasoning in "thinking" field, and the final answer in "message" field.'
            .'If you need to reason about the problem, write your questions in "thought", with context that you need,'
            .'and turn true the "need_to_reasoning" field, when you have a solution turn it to false. Put the message response always'
            .'in "message" field. This is a recursive chains of thoughts, so if you need you can re-do a inference query.' 
            .'If you need to run bash command consecutively, use the ExecuteBashCommandTool and write in "thinking" what command you ran, but the output is not the final answer,' 
            .'put the output in "thought" field and turn true the "need_to_reasoning" field'
            .', so you can reason about the output and make a new inference query, turn false only when you have a final answer.'
            ;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new ExecuteBashCommand,            
        ];
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'message' => $schema->string()->required(),
            'thinking' => $schema->string()->required(),
            'need_to_reasoning' => $schema->boolean()->required() ?? false,
            'thought' => $schema->string(),
        ];
    }
}
