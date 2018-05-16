<template>
    <div class="layout-header-right-ownspace">
        <Dropdown @on-click="tagDropdown">
            <a href="javascript:void(0);">
          <span class="username">
            {{ user.name }}
            <Icon type="arrow-down-b"></Icon>
          </span>
            </a>
            <DropdownMenu slot="list">
                <DropdownItem name="ownspace">个人中心</DropdownItem>
                <DropdownItem name="logout">退出登录</DropdownItem>
            </DropdownMenu>
        </Dropdown>
        <span class="ivu-avatar ivu-avatar-circle ivu-avatar-default ivu-avatar-image">
      <img src="/images/admin/0.jpg">
    </span>
    </div>
</template>

<script>
    export default {
        name: "my-header-ownspace",
        data () {
            return {
                user: {
                    name: '-------'
                }
            }
        },
        mounted () {
            this.$nextTick(function () {

            })
        },
        methods: {
            tagDropdown (name) {
                if (name === 'ownspace') {
                    this.$router.push({
                        name: 'admin.profile'
                    })
                } else {
                    this.$util.cache.clear()
                    this.$router.push({
                        name: 'common.login'
                    })
                }
            }
        },
        components: {}
    }
</script>

<style lang="scss">
.layout-header-right-ownspace {
    display: inline-block;
    .username {
        display: inline-block;
        max-width: 100px;
        word-break: keep-all;
        white-space: nowrap;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: right;
        color: #fff;
    }
    .ivu-avatar {
        background: rgb(97, 159, 231);
        margin-left: 10px;
        margin-right: 20px;
    }
}
</style>