<?php
/* Template Name: Coalition Theme Home Page */
get_header();
//global $post;
$theme     = new Coalition();
$themeData = $theme->getThemeData();
//$theme::varDumpPre($post, true);
?>
    <div class="page-info">
        <h2>Contact</h2>
        <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae modi
            asperiores reiciendis possimus laborum debitis nostrum, illum hic
            atque eaque corrupti commodi totam iste quos quas consequatur
            perspiciatis, soluta magnam.
        </p>
    </div>
    <div class="contact-container">
        <div class="left">
            <p class="title">CONTACT US</p>
			<?php
			echo $theme->showCF7FormViaShortcode( $theme::maybeNullOrEmpty( $themeData, "contact_form_id" ) )
			?>
        </div>
        <div class="right">
            <p class="title">REACH US</p>
            <ul>
                <li><?= bloginfo( "name" ) ?></li>
                <li><?= $theme::maybeNullOrEmpty( $themeData, "address" ) ?>
                </li>
                <li>
					<?php
					if ( $phone = $theme::maybeNullOrEmpty( $themeData, "phone" ) ) {
						?>
                        Phone: <?= $phone ?> <br/>
						<?php
					}
					if ( $fax = $theme::maybeNullOrEmpty( $themeData, "fax" ) ) {
						?>
                        Fax: <?= $fax ?>
						<?php
					}
					?>
                </li>
            </ul>
            <ul class="social-link">
				<?php
				if ( $facebook = $theme::maybeNullOrEmpty( $themeData, "facebook" ) ) {
					?>
                    <li>
                        <a target="_blank" href="<?= $theme::convertUrlToGoodUrl( $facebook ) ?>">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </li>
					<?php
				}
				if ( $twitter = $theme::maybeNullOrEmpty( $themeData, "twitter" ) ) {
					?>
                    <li>
                        <a target="_blank" href="<?= $theme::convertUrlToGoodUrl( $twitter ) ?>">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
					<?php
				}
				if ( $instagram = $theme::maybeNullOrEmpty( $themeData, "instagram" ) ) {
					?>
                    <li>
                        <a target="_blank" href="<?= $theme::convertUrlToGoodUrl( $instagram ) ?>">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </li>
					<?php
				}
				if ( $pinterest = $theme::maybeNullOrEmpty( $themeData, "pinterest" ) ) {
					?>
                    <li>
                        <a target="_blank" href="<?= $theme::convertUrlToGoodUrl( $pinterest ) ?>">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </li>
					<?php
				}

				?>
            </ul>
        </div>
    </div>
    </section>

<?php

get_footer();