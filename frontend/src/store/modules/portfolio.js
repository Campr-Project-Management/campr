import Vue from 'vue';
import * as types from '../mutation-types';
import * as loading from '../loading-types';

const state = {
    currentPortofolio: {},
    portfolios: [],
    portfoliosForSelect: [],
    portfolioLoading: false,
};

const getters = {
    currentPortfolio: state => state.currentPortofolio,
    portfolios: state => state.portfolios,
    portfoliosForSelect: state => state.portfoliosForSelect,
    portfolioLoading: state => state.portfolioLoading,
};

const actions = {
    /**
     * Creates a new portfolio
     * @param {function} commit
     * @param {array} data
     */
    createPortfolio({commit}, data) {
        commit(types.SET_PORTFOLIO_LOADING, {loading: true});
        Vue.http
            .post(
                Routing.generate('app_api_portfolio_create'),
                JSON.stringify(data)
            ).then((response) => {
                if (response.status === 200) {
                    let portfolio = response.data;
                    commit(types.ADD_PORTFOLIO, {portfolio});
                }
                commit(types.SET_PORTFOLIO_LOADING, {loading: false});
            }, (response) => {
                commit(types.SET_PORTFOLIO_LOADING, {loading: false});
            });
    },
    /**
     * Gets portfolios from the API and commits SET_PORTFOLIOS mutation
     * @param {function} commit
     * @param {function} dispatch
     * @return {object}
     */
    async getPortfolios({commit, dispatch}) {
        try {
            dispatch('wait/start', loading.GET_PORTFOLIOS, {root: true});
            let response = await Vue.http.get(Routing.generate('app_api_portfolio_list'));
            let portfolios = response.data;
            commit(types.SET_PORTFOLIOS, {portfolios});

            return response;
        } finally {
            dispatch('wait/end', loading.GET_PORTFOLIOS, {root: true});
        }
    },
    /**
     * Gets a portfolio by ID from the API and commits SET_PORTFOLIO mutation
     * @param {function} commit
     * @param {number} id
     */
    getPortfolioById({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_portfolio_get', {'id': id})).then((response) => {
                if (response.status === 200) {
                    let portfolio = response.data;
                    commit(types.SET_PORTFOLIO, {portfolio});
                }
            }, (response) => {
            });
    },
};

const mutations = {
    /**
     * Sets portfolio to state
     * @param {Object} state
     * @param {Object} portfolio
     */
    [types.SET_PORTFOLIO](state, {portfolio}) {
        state.currentPortofolio = portfolio;
    },
    /**
     * Sets portfolios to state
     * @param {Object} state
     * @param {array} portfolios
     */
    [types.SET_PORTFOLIOS](state, {portfolios}) {
        state.portfolios = portfolios;
        let portfoliosForSelect = [];
        state.portfolios.map( function(portfolio) {
            portfoliosForSelect.push({'key': portfolio.id, 'label': portfolio.name});
        });
        state.portfoliosForSelect = portfoliosForSelect;
    },
    [types.SET_PORTFOLIO_LOADING](state, {loading}) {
        state.portfolioLoading = loading;
    },
    [types.ADD_PORTFOLIO](state, {portfolio}) {
        state.portfolios.push(portfolio);

        let portfoliosForSelect = [];
        state.portfolios.map((portfolio) => {
            portfoliosForSelect.push({'key': portfolio.id, 'label': portfolio.name});
        });
        state.portfoliosForSelect = portfoliosForSelect;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
