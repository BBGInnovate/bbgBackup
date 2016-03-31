<form class="usa-search usa-search-small" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
	<div role="search">
		<label class="usa-sr-only" for="search-field-small">Search</label>
		<input type="text" class="search-field usa-search-input"
			id="search-field-small" 
			placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>"
			value="<?php echo get_search_query() ?>" 
			name="s"
			title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
		<button type="submit">
			<span class="usa-sr-only">Search</span>
		</button>
	</div>
</form>
