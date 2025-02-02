<!--
  - @copyright Copyright (c) 2019 Joas Schilling <coding@schilljs.com>
  -
  - @author Joas Schilling <coding@schilljs.com>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
-->

<template>
	<div>
		<ul v-if="(!loading || addOnClick) && !noResults"
			:style="{'height': height}"
			:class="{'scrollable': scrollable }">
			<Participant
				v-for="participant in participants"
				:key="participant.userId + participant.sessionId"
				:participant="participant"
				@clickParticipant="handleClickParticipant" />
			<!-- 'search for more' empty content to display at the end of the
			participants list, this is useful in case the participants list is used
			to display the results of a search. Upon clicking on it, an event is
			emitted to the parent component in order to be able to focus on it's
			input field -->
			<li
				v-if="displaySearchHint"
				class="participants-list__hint"
				@click="handleClickHint">
				<div class="icon-contacts-dark set-contacts__icon" />
				<p>
					{{ t('spreed', 'Search for more contacts') }}
				</p>
			</li>
		</ul>
		<template v-if="loading">
			<template v-if="addOnClick">
				<LoadingParticipant
					v-for="n in dummyParticipants"
					:key="n" />
			</template>
			<template v-else>
				<div class="icon-loading participants-list__icon" />
				<p class="participants-list__warning">
					{{ t('spreed', 'Contacts loading') }}
				</p>
			</template>
		</template>
		<template v-if="noResults">
			<div class="icon-category-search participants-list__icon" />
			<p class="participants-list__warning">
				{{ t('spreed', 'No results') }}
			</p>
		</template>
	</div>
</template>

<script>

import LoadingParticipant from './Participant/LoadingParticipant'
import Participant from './Participant/Participant'
import Vue from 'vue'

export default {
	name: 'ParticipantsList',

	components: {
		LoadingParticipant,
		Participant,
	},

	props: {
		/**
		 * List of searched users or groups
		 */
		items: {
			type: Array,
			required: true,
		},
		/**
		 * If true, clicking the participant will add it to to the current conversation.
		 * This behavior is used in the right sidebar for already existing conversations.
		 * If false, clicking on the participant will add the participant to the
		 * `selectedParticipants` array in the data.
		 */
		addOnClick: {
			type: Boolean,
			default: true,
		},
		/**
		 * A fixed height can be passed in e.g. ('250px'). This will limit the height of
		 * the ul and make it scrollable.
		 */
		height: {
			type: String,
			default: 'auto',
		},
		/**
		 * Display loading state instead of list.
		 */
		loading: {
			type: Boolean,
			default: false,
		},
		/**
		 * Display no-results state instead of list.
		 */
		noResults: {
			type: Boolean,
			default: false,
		},
		/**
		 * Display 'search for more' empty content at the end of the list.
		 */
		displaySearchHint: {
			type: Boolean,
			default: false,
		},
	},

	data() {
		return {
			selectedParticipants: [],
		}
	},

	computed: {
		token() {
			return this.$store.getters.getToken()
		},

		dummyParticipants() {
			const dummies = 6 - this.items.length
			return dummies > 0 ? dummies : 0
		},

		/**
		 * Creates a new array that combines the items (participants received as a prop)
		 * with the current selectedParticipants so that each participant in the returned
		 * array has a new 'selected' boolean key.
		 * @returns {array} An array of 'participant' objects
		 */
		participants() {
			/**
			 * Compute this only in the new group conversation form.
			 */
			if (this.addOnClick === false) {
				if (this.items !== []) {
					const participants = this.items.slice()
					participants.forEach(item => {
						if (this.selectedParticipants.indexOf(item) !== -1) {
							Vue.set(item, 'selected', true)
						} else {
							Vue.set(item, 'selected', false)
						}
					})
					return participants
				} else {
					return []
				}
			} else {
				return this.items
			}
		},
		scrollable() {
			return this.height !== 'auto'
		},
	},

	methods: {
		async handleClickParticipant(participant) {
			if (this.addOnClick) {
				this.$emit('click', participant)
			} else {
				/**
				 * Remove the clicked participant from the selected participants list
				 */
				if (this.selectedParticipants.indexOf(participant) !== -1) {
					this.selectedParticipants = this.selectedParticipants.filter((selectedParticipant) => {
						return selectedParticipant.id !== participant.id
					})
					this.$emit('updateSelectedParticipants', this.selectedParticipants)
				} else {
					/**
					 * Add the clicked participant from the selected participants list
					 */
					this.selectedParticipants = [...this.selectedParticipants, participant]
					this.$emit('updateSelectedParticipants', this.selectedParticipants)
				}
			}

		},
		handleClickHint() {
			this.$emit('clickSearchHint')
		},

	},
}
</script>

<style lang="scss" scoped>
.scrollable {
	overflow-y: auto;
	overflow-x: hidden;
}

.participants-list {
	&__icon {
		margin-top: 40px;
	}
	&__warning {
		margin-top: 20px;
		text-align: center;
	}
	&__hint {
		margin: 20px 0;
		cursor: pointer;
		p {
			margin-top: 20px;
			text-align: center;
		}
	}
}

</style>
