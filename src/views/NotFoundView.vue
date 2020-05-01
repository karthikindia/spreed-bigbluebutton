<template>
	<div id="emptycontent">
		<div id="emptycontent-icon" class="icon-search" />
		<h2>{{ t('talk_bbb', 'The conversation does not exist') }}</h2>
		<p class="emptycontent-additional">
			{{ t('talk_bbb','Join a conversation or start a new one!') }}
		</p>
	</div>
</template>

<script>
export default {
	name: 'NotFoundView',
}
</script>

<style lang="scss" scoped>
</style>
