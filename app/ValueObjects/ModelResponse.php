<?php

namespace App\ValueObjects;

final class ModelResponse
{
    public string $model = '';
    public string $created_at = '';
    public string $role = '';
    public bool $done = false;
    public string $done_reason = '';
    public int $total_duration = 0;
    public int $prompt_eval_count = 0;
    public int $eval_count = 0;
    public string $content = '';
    public string $message = '';
    public bool $need_to_reasoning = false;
    public string $thought = '';
    public string $thinking = '';

    public function __construct(
        public readonly string $raw,
    ) {
        $envelope = json_decode($this->raw, true);
        $envelope = is_array($envelope) ? $envelope : [];
        $this->model = (string) ($envelope['model'] ?? '');
        $this->created_at = (string) ($envelope['created_at'] ?? '');
        $this->done = (bool) ($envelope['done'] ?? false);
        $this->done_reason = (string) ($envelope['done_reason'] ?? '');
        $this->total_duration = (int) ($envelope['total_duration'] ?? 0);
        $this->prompt_eval_count = (int) ($envelope['prompt_eval_count'] ?? 0);
        $this->eval_count = (int) ($envelope['eval_count'] ?? 0);
        $this->role = (string) ($envelope['message']['role'] ?? '');
        $this->content = (string) ($envelope['message']['content'] ?? '');
        $payload = self::decodePayload($this->content);
        $this->message = (string) ($payload['message'] ?? '');
        $this->need_to_reasoning = (bool) ($payload['need_to_reasoning'] ?? false);
        $this->thought = (string) ($payload['thought'] ?? '');
        $this->thinking = (string) ($payload['thinking'] ?? $envelope['message']['thinking'] ?? '');
        if ($this->message === '' && $payload === []) {
            $this->message = trim($this->content);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private static function decodePayload(string $content): array
    {
        $content = trim($content);

        if (preg_match('/```(?:json)?\s*(.+?)\s*```/s', $content, $matches) === 1) {
            $content = $matches[1];
        }

        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : [];
    }
}
