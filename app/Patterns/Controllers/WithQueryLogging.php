<?php

namespace App\Patterns\Controllers;

use App\Constants\Environments;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Trait WithQueryLogging
 * @package App\Patterns\Controllers
 */
trait WithQueryLogging
{
    private bool $withQueryLogging = false;

    /**
     * @return bool
     */
    private function isWithQueryLogging(): bool
    {
        return $this->withQueryLogging;
    }

    /**
     * @param bool $withQueryLogging
     * @return $this
     */
    public function setWithQueryLogging(bool $withQueryLogging): static
    {
        $this->withQueryLogging = $withQueryLogging;
        return $this;
    }

    protected function logQueries(): void
    {
        if (!$this->isWithQueryLogging() || app()->environment(Environments::PRODUCTION)) {
            return;
        }

        DB::listen(function (QueryExecuted $query) {
            Log::info($this->formatMessage($this->getMessages($query)));
        });
    }

    /**
     * @param QueryExecuted $query
     * @return array
     */
    private function getMessages(QueryExecuted $query): array
    {
        $sql = $query->sql;

        foreach ($query->bindings as $key => $binding) {

            $regex = is_numeric($key)
                ? "/(?<!\?)\?(?=(?:[^'\\\']*'[^'\\']*')*[^'\\\']*$)(?!\?)/"
                : "/:{$key}(?=(?:[^'\\\']*'[^'\\\']*')*[^'\\\']*$)/";

            // Mimic bindValue and only string data types
            if (is_string($binding)) {
                $binding = $this->quote($binding);
            }

            // Make null visible in log
            if (is_null($binding)) {
                $binding = 'null';
            }

            $sql = preg_replace($regex, $binding, $sql, 1);
        }

        return [
            'time' => $query->time,
            'connection_name' => $query->connectionName,
            'query' => $sql,
        ];
    }

    /**
     * @param array $message
     * @return string
     */
    private function formatMessage(array $message): string
    {
        return "Took: {$message['time']} ms, connection_name: {$message['connection_name']}, query: {$message['query']}";
    }

    /**
     * Mimic mysql_real_escape_string
     *
     * @param  string  $value
     * @return string
     */
    private function quote(string $value): string
    {
        $search = ['\\', "\x00", "\n", "\r", "'", '"', "\x1a"];
        $replace = ['\\\\', '\\0', '\\n', '\\r', "\'", '\"', '\\Z'];

        return "'".str_replace($search, $replace, $value)."'";
    }
}
