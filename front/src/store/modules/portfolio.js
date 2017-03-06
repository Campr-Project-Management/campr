import Vue from 'vue';
import * as types from '../mutation-types';

const state = {
    currentItem: {},
    items: [],
    itemsForSelect: [],
};

const getters = {
    portfolio: state => state.currentItem,
    portfolios: state => state.items,
    portfoliosForSelect: state => state.itemsForSelect,
};

const actions = {
    /**
     * Creates a new portfolio
     * @param {function} commit
     * @param {array} data
     */
    testCreatePort({commit}, data) {
        console.log(JSON.stringify(data));
        Vue.http
            .post(Routing.generate('app_api_portfolio_create').substr(1), JSON.stringify(data)).then((response) => {
                console.log(response.data);
            }, (response) => {
                if (response.status === 400) {
                    // implement system to display errors
                    console.log(response.data);
                }
            });
    },
    /**
     * Gets portfolios from the API and commits SET_PORTFOLIOS mutation
     * @param {function} commit
     */
    getPortfolios({commit}) {
        Vue.http
            .get(Routing.generate('app_api_portfolio_list').substr(1)).then((response) => {
                if (response.status === 200) {
                    let portfolios = response.data;
                    commit(types.SET_PORTFOLIOS, {portfolios});
                }
            }, (response) => {
            });
    },
    /**
     * Gets a portfolio by ID from the API and commits SET_PORTFOLIO mutation
     * @param {function} commit
     * @param {number} id
     */
    getPortfolioById({commit}, id) {
        Vue.http
            .get(Routing.generate('app_api_portfolio_get', {'id': id}).substr(1)).then((response) => {
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
        state.currentItem = portfolio;
    },
    /**
     * Sets portfolios to state
     * @param {Object} state
     * @param {array} portfolios
     */
    [types.SET_PORTFOLIOS](state, {portfolios}) {
        state.items = portfolios;
        let portfoliosForSelect = [];
        state.items.map( function(portfolio) {
            portfoliosForSelect.push({'key': portfolio.id, 'label': portfolio.name});
        });
        state.itemsForSelect = portfoliosForSelect;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
