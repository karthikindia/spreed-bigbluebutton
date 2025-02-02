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
	visible: true,
}

const getters = {
	windowIsVisible: (state) => () => {
		return state.visible
	},
}

const mutations = {
	/**
	 * Sets the current visibility state
	 *
	 * @param {object} state current store state;
	 * @param {boolean} value the value;
	 */
	setVisibility(state, value) {
		state.visible = value
	},
}

const actions = {
	/**
	 * Sets the current visibility state
	 *
	 * @param {object} context the context object;
	 * @param {boolean} value the value;
	 */
	setWindowVisibility(context, value) {
		context.commit('setVisibility', value)
	},
}

export default { state, mutations, getters, actions }
