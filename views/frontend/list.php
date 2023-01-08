<div class="user-feedback-list-container">
	<?php if ( current_user_can( 'manage_options' ) ) : ?>
		<h3><?php esc_html_e( 'User Feedbacks', 'user-feedback' ); ?></h3>
		<div class="feedback-list-filter">
			<form action="" class="feedback-list-filter-form">
				<label for="text">
					<input type="text" name="search" placeholder="<?php esc_html_e( 'Search', 'user-feedback' ); ?>">
				</label>
				<label for="submit">
					<button type="submit"><?php esc_html_e( 'Filter', 'user-feedback' ); ?></button>
				</label>
				<label for="">
					<a href="#" class="feedback-list-reset" title="<?php esc_html_e( 'Reset', 'user-feedback' ); ?>"><span class="dashicons dashicons-update-alt"></span></a>
				</label>
				<label for="order">
					<?php esc_html_e( 'Sort by: ', 'user-feedback' ); ?>
					<select name="order">
						<option value="DESC"><?php esc_html_e( 'Newest', 'user-feedback' ); ?></option>
						<option value="ASC"><?php esc_html_e( 'Oldest', 'user-feedback' ); ?></option>
					</select>
				</label>
				<label for="per_page">
					<?php esc_html_e( 'Show: ', 'user-feedback' ); ?>
					<select name="per_page">
						<option value="5">5</option>
						<option value="10">10</option>
						<option value="20">20</option>
						<option value="50">50</option>
					</select>
				</label>
			</form>
		</div>
		<div class="feedback-list">
		</div>
		<div class="loader-container">
			<div class="loader"></div>
		</div>
		<script type="text/html" id="tmpl-user_feedbacks">
			<div class="feedback-list-row-wrapper">
				<# if ( data.feedbacks.length ) { #>
					<# for ( f in data.feedbacks ) { #>
						<div class="feedback-list-row" data-feedback_id={{data.feedbacks[f].id}}>
							<ul class="feedback-item">
								<li>{{data.feedbacks[f].firstname}}</li>
								<li>{{data.feedbacks[f].lastname}}</li>
								<li>{{data.feedbacks[f].email}}</li>
								<li>{{data.feedbacks[f].subject}}</li>
							</ul>
							<div class="feedback-item-details">
							</div>
							<i class="arrow"></i>
							<div class="feedback-row-actions">
								<a href="#" class="feedback-remove" title="<?php esc_html_e( 'Remove', 'user-feedback' ); ?>" data-feedback_id={{data.feedbacks[f].id}}>
									<span class="dashicons dashicons-remove"></span>
								</a>
							</div>
						</div>
					<# } #>
				<# } else { #>
					<p class="feedback-list-message">{{data.message}}</p>
				<# } #>
			</div>
			<div class="feedback-list-pagination">
				<ul class="page-items">
					<# if ( data.prev_page ) { #>
						<li data-page="{{data.prev_page}}" class="page-item"><?php esc_html_e( 'Prev', 'user-feedback' ); ?></li>
					<# } #>
					<# for ( p = 1; p <= data.number_of_pages; ++p ) { #>
						<# if ( data.current_page == p ) { #>
							<li data-page="{{p}}" class="page-item numbered active">{{p}}</li>
						<# } else { #>
							<li data-page="{{p}}" class="page-item numbered">{{p}}</li>
						<# } #>
					<# } #>
					<# if ( data.next_page ) { #>
						<li data-page="{{data.next_page}}" class="page-item"><?php esc_html_e( 'Next', 'user-feedback' ); ?></li>
					<# } #>
				</ul>
			</div>
		</script>
		<script type="text/html" id="tmpl-user_feedback_details">
			<# if ( data ) { #>
				<div class="details-row">
					<p class="field-name"><?php esc_html_e( 'First Name', 'user-feedback' ); ?></p>
					<p class="field-value">{{data.firstname}}</p>
				</div>
				<div class="details-row">
					<p class="field-name"><?php esc_html_e( 'Last Name', 'user-feedback' ); ?></p>
					<p class="field-value">{{data.lastname}}</p>
				</div>
				<div class="details-row">
					<p class="field-name"><?php esc_html_e( 'Email', 'user-feedback' ); ?></p>
					<p class="field-value">{{data.email}}</p>
				</div>
				<div class="details-row">
					<p class="field-name"><?php esc_html_e( 'Subject', 'user-feedback' ); ?></p>
					<p class="field-value">{{data.subject}}</p>
				</div>
				<div class="details-row">
					<p class="field-name"><?php esc_html_e( 'Message', 'user-feedback' ); ?></p>
					<p class="field-value field-message">{{data.message}}</p>
				</div>
			<# } #>	
		</script>
		<script type="text/html" id="tmpl-user_feedback_loader">
			<div class="detail-loader"><div></div><div></div><div></div></div>
		</script>
	<?php else : ?>
		<p class="error-message"><?php esc_html_e( 'You are not authorized to view the content of this page', 'user-feedback' ); ?></p>
	<?php endif; ?>
</div>