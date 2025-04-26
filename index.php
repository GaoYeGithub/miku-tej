<?php
$page_title = "Home";
$current_page = 'index';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

    <!-- Hero -->
    <div class="hero-section text-center" style="background-image: url('./images/hero.jpg'); background-size: cover; background-position: top; padding: 100px 0;">
        <div class="container">
            <h1 class="display-4">Welcome to the World of Hatsune Miku</h1>
            <p class="lead">The world's most famous virtual idol</p>
            <a href="about.php" class="btn btn-light btn-lg mt-3">Learn More</a>
        </div>
    </div>

    <!-- Main -->
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <h2 class="mb-4">Who is Hatsune Miku?</h2>
                <p>Hatsune Miku is a Vocaloid software voicebank developed by Crypton Future Media, represented as a 16-year-old girl with turquoise twintails. She is a virtual singer who has performed at concerts as an animated projection, and has become a global cultural icon.</p>
                
                <p>Miku's name comes from a fusion of the Japanese words for "first" (初, hatsu), "sound" (音, ne), and "future" (ミク, miku). She was initially released in August 2007 for the Vocaloid 2 singing synthesizer and has since become the most popular and well-known Vocaloid character.</p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="feature-box">
                            <h3>Iconic Design</h3>
                            <p>Illustrated by artist KEI, Miku's design features her signature long turquoise twintails and futuristic outfit, which has become instantly recognizable worldwide.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-box">
                            <h3>Musical Innovation</h3>
                            <p>Miku has revolutionized music creation, allowing anyone with the software to produce songs using her voice, leading to thousands of fan-created works.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="images/default/emoji.png" alt="Hatsune Miku" class="img-fluid rounded shadow">
                
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        Quick Facts
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Birthday: August 31, 2007</li>
                        <li class="list-group-item">Height: 158 cm (5'2")</li>
                        <li class="list-group-item">Weight: 42 kg (93 lbs)</li>
                        <li class="list-group-item">Voice: Saki Fujita</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="mb-4">Popular Songs</h2>
                <div class="table-responsive miku-table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Song Title</th>
                                <th>Producer</th>
                                <th>Year</th>
                                <th>Views</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>World is Mine</td>
                                <td>ryo (supercell)</td>
                                <td>2008</td>
                                <td>20M+</td>
                            </tr>
                            <tr>
                                <td>Melt</td>
                                <td>ryo</td>
                                <td>2007</td>
                                <td>15M+</td>
                            </tr>
                            <tr>
                                <td>Love is War</td>
                                <td>ryo</td>
                                <td>2008</td>
                                <td>12M+</td>
                            </tr>
                            <tr>
                                <td>Rolling Girl</td>
                                <td>wowaka</td>
                                <td>2010</td>
                                <td>14M+</td>
                            </tr>
                            <tr>
                                <td>Triple Baka</td>
                                <td>LamazeP</td>
                                <td>2009</td>
                                <td>10M+</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="music.php" class="btn btn-outline-info">View More Songs</a>
                </div>
            </div>
        </div>
    </div>
<?php require_once 'includes/footer.php'; ?>