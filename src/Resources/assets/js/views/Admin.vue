<template>
    <div class="layout">
        <Layout>
            <Header>
                <my-header></my-header>
            </Header>
            <Layout>
                <Sider hide-trigger>
                    <my-menu></my-menu>
                </Sider>
                <Layout>
                    <Content>
                        <my-router-scroll></my-router-scroll>
                        <div class="layout-content-main">
                            <router-view></router-view>
                        </div>
                    </Content>
                    <Footer class="layout-footer-center">2011-2016 © luffyzhao@vip.126.com</Footer>
                </Layout>
            </Layout>
        </Layout>
    </div>
</template>

<script>
    import MyHeader from "../components/my-header";
    import MyRouterScroll from "../components/my-router-scroll";
    import MyMenu from "../components/my-menu";

    export default {
        components: {MyMenu,
            MyRouterScroll,
            MyHeader},
        name: "admin"
    }
</script>

<style scoped>

</style>