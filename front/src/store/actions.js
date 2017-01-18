import * as types from './mutation-types';

/**
 * Gets the module where items needs to be filtered and the filters, commits SET_FILTERS mutation,
 * filters items and commits SET_FILTERED_ITEMS mutation
 * @param {function} commit
 * @param {array} params
 * @param {Object} rootState
 */
export const filterItems = ({commit, rootState}, params) => {
    let filteredItems = [];
    let module = params[2];

    commit(types.SET_FILTERS, params);
    rootState[module].items.map(function(item) {
        let applyFilters = true;
        Object.keys(rootState[module].filters).map(function(key) {
            if (item[key] !== rootState[module].filters[key] && rootState[module].filters[key] != '') {
                applyFilters = false;
            }
        });
        if (applyFilters) {
            filteredItems.push(item);
        }
    });
    commit(types.SET_FILTERED_ITEMS, {filteredItems, module});
};
