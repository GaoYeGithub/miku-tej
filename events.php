<?php
$page_title = "Home";
$current_page = 'index';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="container my-5">
<!-- Concerts -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-0">Upcoming Miku Concerts</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="concert-item mb-4">
                                <h4>Miku Expo 2025 - Tokyo</h4>
                                <p><strong>Date:</strong> May 15-16, 2025</p>
                                <p><strong>Venue:</strong> Tokyo Dome, Tokyo, Japan</p>
                                <p><strong>Status:</strong> <span class="badge badge-warning">Tickets Selling Fast</span></p>
                                <a href="https://magicalmirai.com/2025/tokyo_ticket_en.html" class="btn btn-sm btn-outline-info">Buy Tickets</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="concert-item mb-4">
                                <h4>Miku Expo 2025 - North America Tour</h4>
                                <p><strong>Date:</strong> July 10-25, 2025</p>
                                <p><strong>Venues:</strong> Multiple cities in US and Canada</p>
                                <p><strong>Status:</strong> <span class="badge badge-success">Tickets Available</span></p>
                                <a href="https://mikuexpo.com/" class="btn btn-sm btn-outline-info">Buy Tickets</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="concert-item mb-4">
                                <h4>Magical Mirai 2025</h4>
                                <p><strong>Date:</strong> August 30-31, 2025</p>
                                <p><strong>Venue:</strong> Makuhari Messe, Chiba, Japan</p>
                                <p><strong>Status:</strong> <span class="badge badge-secondary">Coming Soon</span></p>
                                <button class="btn btn-sm btn-outline-secondary" disabled>Join Waitlist</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="concert-item mb-4">
                                <h4>Miku Symphony 2025</h4>
                                <p><strong>Date:</strong> November 15, 2025</p>
                                <p><strong>Venue:</strong> Tokyo International Forum, Tokyo, Japan</p>
                                <p><strong>Status:</strong> <span class="badge badge-secondary">Coming Soon</span></p>
                                <button class="btn btn-sm btn-outline-secondary" disabled>Join Waitlist</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-5">Miku Timeline</h2>
                <div class="timeline">
                    <div class="timeline-container left">
                        <div class="timeline-content">
                            <h3>2007</h3>
                            <p>Hatsune Miku is released as a Vocaloid 2 voice bank by Crypton Future Media</p>
                        </div>
                    </div>
                    <div class="timeline-container right">
                        <div class="timeline-content">
                            <h3>2009</h3>
                            <p>First live concert featuring Miku as a holographic projection in Japan</p>
                        </div>
                    </div>
                    <div class="timeline-container left">
                        <div class="timeline-content">
                            <h3>2010</h3>
                            <p>Miku becomes the first Vocaloid to have a song ("World is Mine") in the Japanese music charts</p>
                        </div>
                    </div>
                    <div class="timeline-container right">
                        <div class="timeline-content">
                            <h3>2014</h3>
                            <p>Miku opens for Lady Gaga's ArtRave tour and appears on The Late Show with David Letterman</p>
                        </div>
                    </div>
                    <div class="timeline-container left">
                        <div class="timeline-content">
                            <h3>2016</h3>
                            <p>First Miku Expo world tour spanning multiple countries across different continents</p>
                        </div>
                    </div>
                    <div class="timeline-container right">
                        <div class="timeline-content">
                            <h3>2020</h3>
                            <p>Virtual concerts gain new popularity during global pandemic, with Miku leading the way</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>