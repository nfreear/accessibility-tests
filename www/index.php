<?php
/**
 * Basic frontend to the pa11y-ci JSON / text accessibility report.
 *
 * @copyright  © 2018-02-13 Nick Freear.
 */

define( 'INDEX_JSON', __DIR__ . '/../index.json' );
define( 'PKG_JSON', __DIR__ . '/../package.json' );
define( 'REPORT_TXT', __DIR__ . '/../report.txt' );

$index_json = json_decode(file_get_contents( INDEX_JSON ));
$pkg_json = json_decode(file_get_contents( PKG_JSON ));
$report_txt = file_get_contents( REPORT_TXT );

$is_pass = ( $index_json->errors === 0 );

if (! $is_pass) {
  header( 'HTTP/1.1 503 Service Unavailable' );
}

?>
<!doctype html><html lang="en" class="<?= $is_pass ? 'ok' : 'error' ?>"><title>Accessibility test results</title>

<style> body { font: 1em sans-serif; margin: 2em auto; max-width: 42em; } h1 { font-weight: normal; }
pre { background: #f8f8f8; border: 1px solid #ccc; margin: 2em 0; padding: 1em; }
.ok #s { color: green; } .error #s { color: #d00; } </style>


<h1> Accessibility test results </h1>

<p id="s"> Passes: <?= $index_json->passes ?> / <?= $index_json->total ?> </p>


<pre id="report"><?= htmlspecialchars( $report_txt ) ?></pre>


<script id="index-json" type="application/json"><?= json_encode( $index_json, JSON_PRETTY_PRINT ) ?></script>

<script id="pkg-json" type="application/json"><?= json_encode( $pkg_json, JSON_PRETTY_PRINT ) ?></script>


<script>
  (function (W, D) {
    var index = JSON.parse(D.querySelector('#index-json').innerText);
    var pkg = JSON.parse(D.querySelector('#pkg-json').innerText);

    console.warn( index );
    console.warn( pkg );

  })(window, document);
</script>
