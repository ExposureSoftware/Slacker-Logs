<?php


namespace ExposureSoftware\SlackLogs\Listeners;


use ExposureSoftware\SlackLogs\Slack\Attachment;
use ExposureSoftware\SlackLogs\Slack\Slacker;
use Illuminate\Container\Container;
use Monolog\Handler\AbstractHandler;
use Monolog\Logger;

/**
 * Class LogHandler
 *
 * @package ExposureSoftware\SlackLogs\Listeners
 */
class LogHandler extends AbstractHandler
{
    /** @var string $hook */
    private $hook;
    /** @var string $user */
    private $user;
    /** @var string $channel */
    private $channel;

    /**
     * LogHandler constructor.
     *
     * @param string $hook
     * @param string $channel
     * @param string $user
     * @param int    $level
     * @param bool   $bubble
     */
    public function __construct($hook, $channel, $user, $level, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->channel = $channel;
        $this->hook = $hook;
        $this->user = $user;
        $this->level = $level;
    }

    /**
     * Handles the incoming log record.
     *
     * @param array $record
     *
     * @return void
     */
    public function handle(array $record)
    {
        if ($record['level'] >= $this->level) {
            /** @var Slacker $slack */
            $slack = Container::getInstance()->make('\ExposureSoftware\SlackLogs\Slack\Slacker', [$this->hook, $this->channel, $this->user]);
            /** @var Attachment $attachment */
            $attachment = Container::getInstance()->make('\ExposureSoftware\SlackLogs\Slack\Attachment', ["New Log Entry", $record['message']]);

            $attachment->setColor($this->colorForLevel($record['level']));
            $attachment->addField('Log Level', $record['level_name'], $record['level']);

            $slack->addAttachment($attachment);
            $slack->setEmoji($this->emojiForLevel($record['level']));

            $slack->send();
        }
    }

    /**
     * Retrieves the appropriate color for the attachment.
     *
     * @param int $level
     *
     * @return string
     */
    private function colorForLevel($level)
    {
        switch (strtoupper($level)) {
            case Logger::DEBUG:
                $color = '#ffff00';
                break;
            case Logger::WARNING:
                $color = '#ffa500';
                break;
            case 'ERROR':
                $color = '#880000';
                break;
            default:
                $color = '#36a64f';
        }

        return $color;
    }

    /**
     * Retrieves the appropriate emoji for the given log level.
     *
     * @param int $level
     *
     * @return string
     */
    private function emojiForLevel($level)
    {
        switch (strtoupper($level)) {
            case Logger::ERROR:
                $emoji = ':fire:';
                break;
            case Logger::WARNING:
                $emoji = ':warning:';
                break;
            default:
                $emoji = ':robot_face:';
        }

        return $emoji;
    }
}
