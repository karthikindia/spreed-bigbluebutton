<template>
	<div class="mainView">
		<LobbyScreen v-if="isInLobby" />
		<template v-else>
			<TopBar :force-white-icons="showChatInSidebar" />
			<ChatView v-if="!showChatInSidebar" :token="token" />
			<template v-else>
				<div class="resp-container">
					<iframe class="resp-iframe"
						allow="geolocation; microphone; camera"
						allowfullscreen
						width="100%"
						height="100%"
						frameborder="0"
						:src="src" />
				</div>
			</template>
		</template>
	</div>
</template>

<script>
import ChatView from '../components/ChatView'
import LobbyScreen from '../components/LobbyScreen'
import TopBar from '../components/TopBar/TopBar'
import { PARTICIPANT } from '../constants'
import isInLobby from '../mixins/isInLobby'

export default {
	name: 'MainView',
	components: {
		ChatView,
		LobbyScreen,
		TopBar,
	},
	mixins: [
		isInLobby,
	],
	props: {
		token: {
			type: String,
			required: true,
		},
	},

	// FIXME reactivate once Signaling is done correctly per conversation again.
	/*
	data() {
		return {
			signaling: null,
		}
	},
	*/

	computed: {
		conversation() {
			return this.$store.getters.conversations[this.token]
		},

		participant() {
			if (typeof this.token === 'undefined') {
				return {
					inCall: PARTICIPANT.CALL_FLAG.DISCONNECTED,
				}
			}

			const participantIndex = this.$store.getters.getParticipantIndex(this.token, this.$store.getters.getParticipantIdentifier())
			if (participantIndex !== -1) {
				return this.$store.getters.getParticipant(this.token, participantIndex)
			}

			return {
				inCall: PARTICIPANT.CALL_FLAG.DISCONNECTED,
			}
		},

		showChatInSidebar() {
			return this.participant.inCall !== PARTICIPANT.CALL_FLAG.DISCONNECTED && this.$store.getters.getCallUrl() !== null
		},

		src() {
			return this.$store.getters.getCallUrl()
		},
	},

	watch: {
		isInLobby: function(isInLobby) {
			// User is now blocked by the lobby
			if (isInLobby && this.participant.inCall !== PARTICIPANT.CALL_FLAG.DISCONNECTED) {
				this.$store.dispatch('leaveCall', {
					token: this.token,
					participantIdentifier: this.$store.getters.getParticipantIdentifier(),
				})
			}
		},
	},

	// FIXME reactivate once Signaling is done correctly per conversation again.
	/*
	watch: {
		token: function(token) {
			this.loadSignalingSettings(token)
		},
	},

	mounted() {
		this.signaling = Signaling
		this.loadSignalingSettings(this.token)
	},

	methods: {
		loadSignalingSettings(token) {
			console.debug('Loading signaling settings for ' + this.token)
			// FIXME reset the settings so we can check it later on if loading is finished
			this.signaling.loadSettings(token)
		},
	},
	*/
}
</script>

<style lang="scss" scoped>
.mainView {
	height: 100%;
	display: flex;
	flex-grow: 1;
	flex-direction: column;
	align-content: space-between;
}
.resp-container {
	position: relative;
	overflow: hidden;
	padding-top: 50%;
	width: 100%;
	height: 100%;
}
.resp-iframe {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	border: 0;
}
</style>
