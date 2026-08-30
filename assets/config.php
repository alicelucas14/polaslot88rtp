<?php

/**
 * [NEW] Smart image finder function for the PROVIDER ICONS.
 * Searches for an icon file with a given base name, prioritizing modern formats.
 *
 * @param string $filename_from_db The logo filename as stored in the database.
 * @return string The full, valid path to the best available icon, or a placeholder.
 */
function find_provider_icon_url($filename_from_db) {
    static $icon_map = null;
    $base_icon_path = 'images/icons/';
    $placeholder_icon = 'images/placeholder.png'; 

    if ($icon_map === null) {
        $icon_map = [];
        if (is_dir($base_icon_path)) {
            $files = scandir($base_icon_path);
            foreach ($files as $f) {
                if ($f !== '.' && $f !== '..') {
                    $icon_map[$f] = true;
                }
            }
        }
    }

    $filename_from_db = basename($filename_from_db);
    $base_name = pathinfo($filename_from_db, PATHINFO_FILENAME);

    if (pathinfo($filename_from_db, PATHINFO_EXTENSION)) {
        if (isset($icon_map[$filename_from_db])) {
            return $base_icon_path . $filename_from_db;
        }
    }

    $preferred_extensions = ['webp', 'png', 'jpg', 'jpeg', 'gif'];
    foreach ($preferred_extensions as $ext) {
        $candidate = $base_name . '.' . $ext;
        if (isset($icon_map[$candidate])) {
            return $base_icon_path . $candidate;
        }
    }
    
    return $placeholder_icon;
}

/**
 * Smart image finder function for the GAME IMAGES with high-speed in-memory indexing.
 */
function find_public_game_image_url($filename_from_db) {
    static $game_map = null;
    $base_image_path = 'images/games/';
    $placeholder_image = 'images/placeholder.png';

    if ($game_map === null) {
        $game_map = [];
        if (is_dir($base_image_path)) {
            $files = scandir($base_image_path);
            foreach ($files as $f) {
                if ($f !== '.' && $f !== '..') {
                    $game_map[$f] = true;
                }
            }
        }
    }

    $filename_from_db = basename($filename_from_db);
    $base_name = pathinfo($filename_from_db, PATHINFO_FILENAME);

    if (pathinfo($filename_from_db, PATHINFO_EXTENSION)) {
        if (isset($game_map[$filename_from_db])) {
            return $base_image_path . $filename_from_db;
        }
    }

    // Build candidate base names (supporting both 2-digit and 3-digit zero-padding, e.g. -99, -099, -100)
    $candidate_bases = [$base_name];
    if (preg_match('/^([a-zA-Z0-9]+)[-_](\d+)$/', $base_name, $m)) {
        $prefix = $m[1];
        $num = (int)$m[2];
        $candidate_bases[] = $prefix . '-' . $num;
        $candidate_bases[] = $prefix . '-' . sprintf('%02d', $num);
        $candidate_bases[] = $prefix . '-' . sprintf('%03d', $num);
        $candidate_bases[] = $prefix . '_' . $num;
        $candidate_bases[] = $prefix . '_' . sprintf('%02d', $num);
        $candidate_bases[] = $prefix . '_' . sprintf('%03d', $num);
    }
    $candidate_bases = array_unique($candidate_bases);

    $preferred_extensions = ['webp', 'png', 'jpg', 'jpeg', 'gif'];
    foreach ($candidate_bases as $cb) {
        foreach ($preferred_extensions as $ext) {
            $candidate = $cb . '.' . $ext;
            if (isset($game_map[$candidate])) {
                return $base_image_path . $candidate;
            }
        }
    }

    return $placeholder_image;
}

/**
 * [MODIFIED] The rtpCard function now accepts a new argument for the provider icon path.
 */
function rtpCard($prov, $img_path, $provider_icon_path, $daftar, $number) {
    static $no = 0;
    $no++;

    $gametop = '';
    if ($number <= 10) {
        $gametop = '<div class="hot-game"></div>';
    } elseif ($number <= 15) {
        $gametop = '<div class="top-game"></div>';
    }

    return '<div class="col-lg-4 col-6">
        <div data-prov="'.$prov.'" class="rtp-card animate__animated animate__bounceIn">
        '.$gametop.'
            <div class="row g-1">
                <div class="col-lg-6 align-self-center">
                    <div class="place-img-rtp">
                        <button onclick="location.href=\''.$daftar.'\';" class="btn-play shadow"><i class="lni lni-heart-fill"></i> Mari Bermain</button>
                    <img class="lazy shadow rtp-card-img" src="images/loading.svg" data-src="'. $img_path .'" alt="game image">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="pola-wrapper shadow text-center">
                        <div class="icon-providers"><img src="'. $provider_icon_path .'" alt="icon provider"></div>
                        <h4><i class="lni lni-bar-chart"></i> Pola Slot:</h4>
                        <hr>
                        <table class="table-pola text-center">
                            <tbody>
                            <tr id="pola-slot-1-'.$no.'"></tr>
                            <tr id="pola-slot-2-'.$no.'"></tr>
                            <tr id="pola-slot-3-'.$no.'"></tr>
                            </tbody>
                        </table>
                        <h5 class="mt-3 fw-bold" id="jam-gacor-'.$no.'"></h5>
                        <div class="percent">
                            <p id="percent-txt-'.$no.'" style="z-index: 15">00%</p>
                            <div id="percent-bar-'.$no.'" class="percent-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="" style="width: 0"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
 }

 // Helper functions
 function ftab($db_stat, $tname, $colname) {
     global $data;
     if (!$data) {
         require_once __DIR__ . '/data.php';
     }
     $fdb = "SELECT " . $db_stat . " FROM " . $tname . " LIMIT 1";
     $qdb = mysqli_query($data, $fdb);
     if ($qdb && mysqli_num_rows($qdb) > 0) {
         $fetch = mysqli_fetch_assoc($qdb);
         return html_entity_decode($fetch[$colname] ?? '');
     }
     return '';
 }
 function dayindo() { $dindo = date("l"); switch($dindo) { case 'Monday': return 'Senin'; break; case 'Tuesday': return 'Selasa'; break; case 'Wednesday': return 'Rabu'; break; case 'Thursday': return 'Kamis'; break; case 'Friday': return 'Jumat'; break; case 'Saturday': return 'Sabtu'; break; case 'Sunday': return 'Minggu'; break; } }
 function bulanindo() { $bulanindo = date("F"); switch($bulanindo) { case 'January': return 'Januari'; break; case 'February': return 'Februari'; break; case 'March': return 'Maret'; break; case 'April': return 'April'; break; case 'May': return 'Mei'; break; case 'June': return 'Juni'; break; case 'July': return 'Juli'; break; case 'August': return 'Agustus'; break; case 'September': return 'September'; break; case 'October': return 'Oktober'; break; case 'November': return 'November'; break; case 'December': return 'Desember'; break; } }
?>