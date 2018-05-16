<template>
    <div class="layout-router">
        <div class="layout-router-scroll">
            <div class="layout-router-scroll-dropdown">
                <Dropdown :transfer="true" @on-click="tagDropdown">
                    <Button type="primary" size="small">
                        标签选项
                        <Icon type="arrow-down-b"></Icon>
                    </Button>
                    <DropdownMenu slot="list">
                        <DropdownItem name="closeOther">关闭其他</DropdownItem>
                        <DropdownItem name="closeAll">关闭所有</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </div>
            <div class="layout-router-scroll-body">
                <Tag type="dot" v-for="(item, index) in openPageList" :key="index" :color="currentPage === item.name ? 'blue':'default'" :closable="item.name !== 'home'" :name="index" @on-close="tagClose" @click.native="menuRouter(item.name)">{{ item.meta.title }}</Tag>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "my-router-scroll",
        computed: {
            currentPage () {
                return this.$store.state.App.currentPage
            },
            openPageList () {
                return this.$store.state.App.openPageList
            }
        },
        methods: {
            tagClose (event, index) {
                this.$store.commit('closePage', {
                    index,
                    vm: this
                })
            },
            tagDropdown (name) {
                this.$store.commit('closePages', {
                    name,
                    vm: this
                })
            },
            menuRouter (name) {
                this.$router.push({
                    name
                })
            }
        },
    }
</script>

<style lang="scss">
    .layout-router {
        height: 42px;
        overflow: hidden;
        background: #fff;
        border-bottom: 2px solid #464c5b;
    &-scroll {
         position: relative;
         box-sizing: border-box;
         padding-right: 120px;
         width: 100%;
         height: 100%;
    &-dropdown {
         position: absolute;
         right: 0;
         top: 0;
         box-sizing: border-box;
         padding-top: 8px;
         text-align: center;
         width: 110px;
         height: 100%;
         background: #fff;
         box-shadow: -3px 0 15px 3px rgba(0,0,0,.1);
         z-index: 10;
     }
    &-body {
         position: absolute;
         padding: 2px 10px;
         overflow: visible;
         white-space: nowrap;
         transition: left 0.3s ease;
     }
    }
    }
</style>