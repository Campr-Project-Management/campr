/**
 * Sets filtered items to state
 * @param {Object} rootState
 * @param {array} filteredItems
 * @param {string} module
 */
export const SET_FILTERED_ITEMS = (rootState, {filteredItems, module}) => {
    rootState[module].filteredItems.items = filteredItems;
};
