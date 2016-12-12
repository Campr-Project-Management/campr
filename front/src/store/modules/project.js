import * as types from '../mutation-types'

const state = {}

const getters = {
  projects: state => state.projects
}

const actions = {
  /**
   * Gets projects from the API and commits SET_PROJECTS mutation
   * @param commit
   */
  getProjects ({ commit }) {
    // TODO: API
    var projects = [1, 2, 3, 4, 5, 6, 7]
    commit(types.SET_PROJECTS, { projects })
  }
}

const mutations = {
  /**
   * Sets projects to state
   * @param state
   * @param projects
   */
  [types.SET_PROJECTS] (state, { projects }) {
    state.projects = projects
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}
