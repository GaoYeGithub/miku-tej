
<?php
$page_title = "About";
$current_page = 'about';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'includes/config.php';
?>

<div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4 text-center">About Hatsune Miku</h1>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="about-section">
                    <h2>Who is Hatsune Miku?</h2>
                    <p class="lead">Hatsune Miku is a Vocaloid software voicebank developed by Crypton Future Media and its official anthropomorphic mascot.</p>
                    
                    <p>Hatsune Miku is a virtual singer whose name means "first sound of the future." She was initially released on August 31, 2007, as the first offering in Crypton's "Character Vocal Series" for the Vocaloid 2 singing synthesizer software.</p>
                    
                    <p>Her voice is sampled from voice actress Saki Fujita, and her anthropomorphic persona was created by illustrator KEI. With her signature long turquoise twintails and futuristic outfit, Miku has become one of the most recognizable and beloved virtual characters in the world.</p>
                    
                    <p>More than just software, Miku has become a cultural phenomenon, performing "live" as a projection at concerts, appearing in video games, and becoming a source of inspiration for countless artists, musicians, and fans worldwide.</p>
                </div>
                
                <div class="about-section">
                    <h2>The Technology Behind Miku</h2>
                    <p>Hatsune Miku is powered by Yamaha's Vocaloid technology, a singing voice synthesizer that allows users to synthesize "singing" by typing in lyrics and melody. The software uses recorded vocal samples from voice actors that are analyzed and processed to create a database of phonemes that can be combined to form any lyrics the user desires.</p>
                    
                    <p>The Vocaloid software works by:</p>
                    <ol>
                        <li>Breaking down lyrics into phonetic components</li>
                        <li>Matching these components with recorded samples</li>
                        <li>Adjusting pitch, timing, and other parameters to match the desired melody</li>
                        <li>Synthesizing the complete vocal track</li>
                    </ol>
                    
                    <p>This technology has evolved significantly since Miku's initial release, with improvements in sound quality, naturalness, and expressiveness with each new version.</p>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="about-section">
                    <h3>Miku's Profile</h3>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Full Name</th>
                                <td>Hatsune Miku (初音ミク)</td>
                            </tr>
                            <tr>
                                <th>Age</th>
                                <td>16 years</td>
                            </tr>
                            <tr>
                                <th>Height</th>
                                <td>158 cm (5'2")</td>
                            </tr>
                            <tr>
                                <th>Weight</th>
                                <td>42 kg (93 lbs)</td>
                            </tr>
                            <tr>
                                <th>Birthday</th>
                                <td>August 31, 2007</td>
                            </tr>
                            <tr>
                                <th>Voice Provider</th>
                                <td>Saki Fujita</td>
                            </tr>
                            <tr>
                                <th>Character Design</th>
                                <td>KEI</td>
                            </tr>
                            <tr>
                                <th>Developer</th>
                                <td>Crypton Future Media</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="about-section">
                    <h3>Did You Know?</h3>
                    <ul>
                        <li>Miku's name consists of hatsu (初, "first"), ne (音, "sound"), and miku (未来, "future")</li>
                        <li>She holds the Guinness World Record for "First Digital Pop Star to headline major concerts as a hologram"</li>
                        <li>Miku has opened for Lady Gaga during her ArtRave: The Artpop Ball tour</li>
                        <li>She has appeared on The Late Show with David Letterman</li>
                        <li>There are official Miku-themed cars in the Japanese Super GT racing series</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <?php require_once 'includes/footer.php'; ?>
