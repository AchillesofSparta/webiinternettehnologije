<?php
    namespace App\Core\Fingerprint;

    use App\Core\Fingerprint\FingerprintProvider;
    use App\Core\Fingerprint\BasicFingerprintProvider;

    class BasicFingerprintProviderFactory {
        public function getInstance(string $arraySource): BasicFingerprintProvider{
            switch ($arraySource) {
                case "SERVER" :
                    return new BasicFingerprintProvider($_SERVER);
            }

            return new BasicFingerprintProvider($_SERVER);
        }
    }