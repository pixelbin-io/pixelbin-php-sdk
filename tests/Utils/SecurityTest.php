<?php

declare(strict_types=1);

namespace Pixelbin\Tests\Utils {
    use Pixelbin\Utils\Security;
    use Pixelbin\Common\Exceptions;
    use PHPUnit\Framework\TestCase;

    final class SecurityTest extends TestCase
    {
        public function test_SignUrl()
        {
            $signedURL = Security::signURL(
                "https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg",
                20,
                "459337ed-f378-4ddf-bad7-d7a4555c4572",
                "dummy-token",
            );

            $signedUrlObj = parse_url($signedURL);
            parse_str($signedUrlObj["query"], $searchParams);

            $keys = ['pbs', 'pbe', 'pbt'];

            foreach ($keys as $idx => $value) {
                $key = $keys[$idx];
                $this->assertTrue(isset($searchParams[$key]), "$key searchParam should be present");
            }
        }

        public function test_SignUrlWithQuery()
        {
            $signedURL = Security::signURL(
                "https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg?testquery1=testval&testquery2=testval",
                20,
                "459337ed-f378-4ddf-bad7-d7a4555c4572",
                "dummy-token",
            );

            $signedUrlObj = parse_url($signedURL);
            parse_str($signedUrlObj["query"], $searchParams);

            $keys = ['pbs', 'pbe', 'pbt'];

            foreach ($keys as $idx => $value) {
                $key = $keys[$idx];
                $this->assertTrue(isset($searchParams[$key]), "$key searchParam should be present");
                if (str_contains($key, "testquery")) {
                    $this->assertEquals("testval", $value);
                }
            }
        }

        public function test_SignUrlCustomDomain()
        {
            $signedURL = Security::signURL(
                "https://krit.imagebin.io/v2/original/__playground/playground-default.jpeg",
                20,
                "08040485-dc83-450b-9e1f-f1040044ae3f",
                "dummy-token-2",
            );

            $signedUrlObj = parse_url($signedURL);
            parse_str($signedUrlObj["query"], $searchParams);

            $keys = ['pbs', 'pbe', 'pbt'];

            foreach ($keys as $idx => $value) {
                $key = $keys[$idx];
                $this->assertTrue(isset($searchParams[$key]), "$key searchParam should be present");
            }
        }

        public function test_FailureWhenEmptyUrlProvided()
        {
            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            Security::signURL("", 20, "1", "dummy-token");
        }

        public function test_FailureWhenEmptyAccessKeyProvided()
        {
            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            Security::signURL("https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg", 20, "", "dummy-token");
        }

        public function test_FailureWhenEmptyTokenProvided()
        {
            $this->expectException(Exceptions\PDKIllegalArgumentError::class);
            Security::signURL("https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg", 20, "1", "");
        }

        public function test_FailureWhenEmptyExpirySecondsProvided()
        {
            $this->expectException(\TypeError::class);
            Security::signURL("https://cdn.pixelbin.io/v2/dummy-cloudname/original/__playground/playground-default.jpeg", null, "1", "dummy-token");
        }
    }
}
