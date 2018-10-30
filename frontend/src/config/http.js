export default {
    headers: {
        'Authorization': `Bearer ${window.user.api_token}`,
        'X-Requested-With': 'XMLHttpRequest',
    },
};
