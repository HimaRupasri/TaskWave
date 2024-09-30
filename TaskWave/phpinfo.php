<?php
// Check if OpenSSL extension is enabled
if (extension_loaded('openssl')) {
    echo "OpenSSL extension is enabled.";
} else {
    echo "OpenSSL extension is not enabled.";
}
?>
