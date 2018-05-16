<template>
    <Menu theme="dark" :open-names="['1']" accordion mode="vertical" width="auto" :active-name="currentPage" @on-select="menuRouter">
        <MenuItem name="admin.home">
            <Icon type="ios-home"></Icon>
            首页
        </MenuItem>
        <template v-for="(item, key) in menuLists">
            <MenuItem v-if="!item.children" :name="item.name">
                <Icon :type="item.icon"></Icon>
                {{item.title}}
            </MenuItem>

            <Submenu v-if="item.children" :name="item.name">
                <template slot="title">
                    <Icon :type="item.icon"></Icon>
                    {{item.title}}
                </template>
                <template v-for="(value, index) in item.children">
                    <MenuItem v-if="!value.children" :name="value.name">{{value.title}}</MenuItem>
                    <MenuGroup v-if="value.children" :title="value.title">
                        <MenuItem v-for="(val, i) in value.children" :key="i" :name="val.name">{{val.title}}</MenuItem>
                    </MenuGroup>
                </template>
            </Submenu>
        </template>
    </Menu>
</template>

<script>
    import Tools from '@/mixins/tools'
    export default {
        name: "my-menu",
        mixins: [Tools],
        data(){
            return {
                lists: [{
                    id: 1,
                    name: 'management.index',
                    icon: 'ios-paper',
                    title: '内容管理',
                    parent_id: 0
                }, {
                    id: 2,
                    name: 'article.index',
                    icon: 'ios-paper',
                    title: '文章管理',
                    parent_id: 1
                }]
            }
        },
        methods:{
            menuRouter (name) {
                this.$router.push({
                    name
                })
            }
        },
        computed: {
            currentPage () {
                return this.$store.state.App.currentPage
            },
            menuLists(){
                return this.toTree(this.lists)
            }
        }
    }
</script>

<style scoped>

</style>