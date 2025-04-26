<?php
$page_title = "Music";
$current_page = 'music';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'includes/config.php';
require_once 'vendor/autoload.php';
    
// database connection
function load_yaml_file($file_path) {
    return \Symfony\Component\Yaml\Yaml::parseFile($file_path);
}

// Load music
$music_data = load_yaml_file('includes/data/music.yaml');

// Extract data from database
$categories = $music_data['categories'];
$songs = $music_data['songs'];
$producers = $music_data['producers'];

// category filter from database
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';

// Filter songs
$filtered_songs = ($category_filter !== 'all') ? 
    array_filter($songs, function($song) use ($category_filter) {
        return in_array($category_filter, $song['categories']);
    }) : 
    $songs;

// current song
$current_song = null;
if (isset($_GET['song'])) {
    $song_id = (int)$_GET['song'];
    foreach ($songs as $song) {
        if ($song['id'] === $song_id) {
            $current_song = $song;
            break;
        }
    }
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4 text-center">Hatsune Miku Music Collection</h1>
            <p class="text-center mb-5">Explore Miku's most iconic songs and discover new favorites.</p>
        </div>
    </div>
    
    <!-- Music -->
    <?php if ($current_song): ?>
    <div class="row mb-5">
        <div class="col-12">
            <div class="card music-player">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="images/<?php echo str_replace(' ', '_', strtolower($current_song['album'])); ?>.jpg" 
                                 alt="<?php echo $current_song['album']; ?>" 
                                 class="img-fluid album-cover rounded">
                        </div>
                        <div class="col-md-8">
                            <h3><?php echo $current_song['title']; ?></h3>
                            <p class="lead">By <?php echo $current_song['producer']; ?> (<?php echo $current_song['year']; ?>)</p>
                            <p><?php echo $current_song['description']; ?></p>
                            
                            <div class="audio-player mt-4">
                                <audio controls class="w-100">
                                    <source src="<?php echo $current_song['audio_sample']; ?>" type="audio/mp3">
                                    Your browser does not support the audio element.
                                </audio>
                                <p class="text-muted mt-2">
                                    <small>Note: I don't know copy right laws. Plz don't sue me</small>
                                </p>
                            </div>
                            
                            <div class="song-details mt-4">
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>Album:</strong> <?php echo $current_song['album']; ?></p>
                                        <p><strong>Length:</strong> <?php echo $current_song['length']; ?></p>
                                    </div>
                                    <div class="col-6">
                                        <p><strong>Views:</strong> <?php echo $current_song['views']; ?></p>
                                        <p><strong>Categories:</strong> 
                                            <?php 
                                            $category_names = array_map(function($cat) use ($categories) {
                                                return $categories[$cat];
                                            }, $current_song['categories']);
                                            echo implode(', ', $category_names);
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="shop.php?category=music" class="btn btn-info">Purchase Full Track</a>
                                <a href="music.php" class="btn btn-outline-secondary ml-2">Back to Song List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="category-filter">
                <?php foreach ($categories as $cat_key => $cat_name): ?>
                <a href="music.php?category=<?php echo $cat_key; ?>" class="btn <?php echo $category_filter === $cat_key ? 'btn-info' : 'btn-outline-info'; ?> m-1"><?php echo $cat_name; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-hover music-table">
                    <thead class="thead-light">
                        <tr>
                            <th>Title</th>
                            <th>Producer</th>
                            <th>Year</th>
                            <th>Length</th>
                            <th>Views</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($filtered_songs)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No songs found in this category.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($filtered_songs as $song): ?>
                                <tr class="<?php echo ($current_song && $current_song['id'] === $song['id']) ? 'table-info' : ''; ?>">
                                    <td><?php echo $song['title']; ?></td>
                                    <td><?php echo $song['producer']; ?></td>
                                    <td><?php echo $song['year']; ?></td>
                                    <td><?php echo $song['length']; ?></td>
                                    <td><?php echo $song['views']; ?></td>
                                    <td>
                                        <a href="music.php?song=<?php echo $song['id']; ?>&category=<?php echo $category_filter; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-play"></i> Play
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="mb-4">Producer Spotlight</h2>
        </div>
        
        <?php foreach ($producers as $index => $producer): ?>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <img src="images/producers/<?php echo $producer['image']; ?>" alt="<?php echo $producer['name']; ?>" class="img-fluid rounded-circle producer-image">
                        </div>
                        <div class="col-8">
                            <h4><?php echo $producer['name']; ?></h4>
                            <p><?php echo $producer['description']; ?></p>
                            <p><strong>Notable songs:</strong> <?php echo $producer['notable_songs']; ?></p>
                            <a href="music.php?category=all&producer=<?php echo urlencode(strtolower(explode(' ', $producer['name'])[0])); ?>" class="btn btn-sm btn-outline-info">View All Songs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>