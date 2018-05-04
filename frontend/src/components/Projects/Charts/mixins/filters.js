export default {
    filters: {
        chartData(value) {
            let data = {};
            if (!value) {
                return data;
            }

            value.forEach((item) => {
                data[item.name] = item.values;
            });

            return data;
        },
    },
};
