<?php if ( ! empty( $videoID ) ) : ?>
<div <?php $this->component_class(); ?> data-js-simple_video="<?php echo $videoType; ?>">
	<div class="video-defer-container">
		<div class="video-defer" data-embed="<?php echo $videoID; ?>">
			<?php if ( ! empty( $fp_icon_slug ) ) : ?>
				<div class="play-button-icon video-remove <?php echo $fp_icon_slug; ?>"></div>
			<?php else : ?>
				<div class="play-button video-remove"></div>
			<?php endif; ?>

			<?php if ( ! empty( $fp_video_thumb ) ) : ?>
				<img class="video-remove cover-image custom-image" alt="poster-image" src="<?php echo $fp_video_thumb_src; ?>" srcset="<?php echo $fp_video_thumb_srcset; ?>"sizes="<?php echo $fp_video_thumb_sizes; ?>">
			<?php elseif ( $videoType == 'youtube' ) : ?>
				<img class="video-remove cover-image" src="https://img.youtube.com/vi/<?php echo $videoID; ?>/maxresdefault.jpg" alt="default-image">
			<?php elseif ( $videoType == 'vimeo' ) : ?>
				<img class="video-remove cover-image" src="<?php echo unserialize( file_get_contents( "http://vimeo.com/api/v2/video/$videoID.php" ) )[0]['thumbnail_large']; ?>" alt="default-image">
			<?php endif; ?>

			<div class="video-player-id" id="player-<?php echo $module->node; ?>"></div>
		</div>
	</div>
</div>
<?php else : ?>
<h3>Missing/Invalid Video ID</h3>
<?php endif; ?>