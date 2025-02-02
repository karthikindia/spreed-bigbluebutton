/**
 * @copyright Copyright (c) 2019 Marco Ambrosini <marcoambrosini@pm.me>
 *
 * @author Marco Ambrosini <marcoambrosini@pm.me>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

const state = {
	token: '',
	fileIdForToken: null,
	callUrl: null,
}

const getters = {
	getToken: (state) => () => {
		return state.token
	},
	getCallUrl: (state) => () => {
		return state.callUrl
	},
	getFileIdForToken: (state) => () => {
		return state.fileIdForToken
	},
}

const mutations = {
	/**
	 * Updates the token
	 *
	 * @param {object} state current store state;
	 * @param {string} newToken The token of the active conversation
	 */
	updateToken(state, newToken) {
		state.token = newToken
	},

	updateCallUrl(state, newCallUrl) {
		state.callUrl = newCallUrl !== '' ? newCallUrl : null
	},

	/**
	 * Updates the file ID for the current token
	 *
	 * @param {object} state current store state
	 * @param {string} newToken The token of the active conversation
	 * @param {int} newFileId The file ID of the active conversation
	 */
	updateTokenAndFileIdForToken(state, { newToken, newFileId }) {
		state.token = newToken
		state.fileIdForToken = newFileId
	},
}

const actions = {

	/**
	 * Updates the token
	 *
	 * @param {object} context default store context;
	 * @param {string} newToken The token of the active conversation
	 */
	updateToken(context, newToken) {
		context.commit('updateToken', newToken)
	},

	updateCallUrl(context, newCallUrl) {
		context.commit('updateCallUrl', newCallUrl)
	},

	/**
	 * Updates the file ID for the current token
	 *
	 * @param {object} context default store context
	 * @param {string} newToken The token of the active conversation
	 * @param {int} newFileId The file ID of the active conversation
	 */
	updateTokenAndFileIdForToken(context, { newToken, newFileId }) {
		context.commit('updateTokenAndFileIdForToken', { newToken, newFileId })
	},
}

export default { state, mutations, getters, actions }
