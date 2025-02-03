<?php

namespace Pixelbin\Tests\Platform\UploaderUtils {
    use Amp\Parallel\Worker\Task;
    use Amp\Sync\Channel;
    use Amp\Cancellation;

    class UploadChunkTestTask implements Task
    {
        public function run(?Channel $channel = null, ?Cancellation $cancellation = null): mixed
        {
            return ['status_code' => 204, 'status' => 'success'];
        }
    }
}
