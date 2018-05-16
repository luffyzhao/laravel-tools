export default {
    methods:{
        toTree(data) {
            data.forEach(function (item) {
                delete item.children;
            });
            var map = {};
            data.forEach(function (item) {
                map[item.id] = item;
            });
            var val = [];
            data.forEach(function (item) {
                var parent = map[item.parent_id];
                if (parent) {
                    (parent.children || ( parent.children = [] )).push(item);
                } else {
                    val.push(item);
                }
            });
            return val;
        }
    }
}