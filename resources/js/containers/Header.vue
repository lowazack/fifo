<template>
  <CHeader>
    <CToggler
      in-header
      class="ml-3 d-md-none"
      @click="$store.commit('toggleSidebarMobile')"
    />
    <CToggler
      in-header
      class="ml-3 d-sm-down-none"
      @click="toggleSidebar"
    />
    <CHeaderBrand class="mx-auto d-lg-none" to="/">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72.77 35.76"><g id="logo"><path fill="#FFF" d="M26.21,11.09V35.23H20.4v-20h-11v20H3.65v-20H0V11.09H3.65V8A7.25,7.25,0,0,1,6.24,2.21,10.17,10.17,0,0,1,13.06,0,13.34,13.34,0,0,1,20.5,2.26L18.58,6.1a9.63,9.63,0,0,0-4.47-1.4,5.1,5.1,0,0,0-3.48,1.16A3.86,3.86,0,0,0,9.31,8.93v2.16Z"/><path class="cls-1" d="M42.43,0a13.3,13.3,0,0,1,7.44,2.26L48,6.1a10.05,10.05,0,0,0-4.46-1.4,4.39,4.39,0,0,0-3.17,1.16,4,4,0,0,0-1.2,3.07v2.16H45.7v4.17H39.22v20H33.46v-20H29.81V11.09h3.65V8a7.41,7.41,0,0,1,2.47-5.81A9.4,9.4,0,0,1,42.43,0Z"/><path fill="#FFF" d="M50,14a13.08,13.08,0,0,1,9.45-3.65A13.18,13.18,0,0,1,69,14,12,12,0,0,1,72.77,23,12,12,0,0,1,69,32.11a13.18,13.18,0,0,1-9.48,3.65A13.14,13.14,0,0,1,50,32.14,12.11,12.11,0,0,1,46.22,23,12.06,12.06,0,0,1,50,14Zm14.9,3.45a7.61,7.61,0,0,0-10.9,0,7.84,7.84,0,0,0-2.23,5.67A7.67,7.67,0,0,0,54,28.68a7.65,7.65,0,0,0,10.9,0,7.67,7.67,0,0,0,2.23-5.59A7.84,7.84,0,0,0,64.92,17.42Z"/></g></svg>
    </CHeaderBrand>
    <CDropdown
        v-if="dropdowns.length"
        :toggler-text="pageTitle"
        class="d-md-down-none mr-auto"
        size="lg"
        color="link"
        addTogglerClasses="h4 p-3 m-0"
      >
      <CDropdownItem v-for="(dropdown, index) in dropdowns" :key="index" :class="{ 'active': dropdown.active }" :to="{ name: dropdown.to }">{{ dropdown.name }}</CDropdownItem>
    </CDropdown>
    <CCol v-else class="d-md-down-none mr-auto p-3">
      <h4 class="m-0">{{ pageTitle }}</h4>
    </CCol>
    <CHeaderNav>
      <CHeaderNavItem class="px-3 c-d-legacy-none">
        <button
          @click="() => $store.commit('toggle', 'darkMode')"
          class="c-header-nav-btn"
        >
          <CIcon v-if="$store.state.darkMode" name="cil-sun"/>
          <CIcon v-else name="cil-moon"/>
        </button>
      </CHeaderNavItem>
      <DropdownTimetracking/>
      <DropdownNew/>
      <DropdownNotifications/>
      <DropdownTasks/>
      <DropdownMessages/>
      <DropdownAccount/>
      <CHeaderNavItem class="px-3">
        <button
          class="c-header-nav-btn"
          @click="$store.commit('toggle', 'asideShow')"
        >
          <CIcon size="lg" name="cil-applications-settings" class="mr-2"/>
        </button>
      </CHeaderNavItem>
    </CHeaderNav>
    <CSubheader class="justify-content-between px-0 px-sm-3" v-if="children.length || childrenRight.length ">
      <CHeaderNav class="mr-sm-auto" v-if="children.length">
        <CHeaderNavItem v-for="(link, index) in children" :key="index">
          <CHeaderNavLink class="px-2 px-sm-3" v-if="link.href" :to="link.href" :active="link.active" v-html="link.name" />
          <CHeaderNavLink class="px-2 px-sm-3" v-else-if="link.to" :to="{ name: link.to }" :active="link.active" v-html="link.name" />
        </CHeaderNavItem>
      </CHeaderNav>
      <CHeaderNav v-if="childrenRight.length">
        <CHeaderNavItem v-for="(link, index) in childrenRight" :key="index">
          <CHeaderNavLink class="px-2 px-sm-3" v-if="link.href" :to="link.href" :active="link.active" v-html="link.name" />
          <CHeaderNavLink class="px-2 px-sm-3" v-else-if="link.to" :to="{ name: link.to }" :active="link.active" v-html="link.name" />
        </CHeaderNavItem>
      </CHeaderNav>
    </CSubheader>
  </CHeader>
</template>

<script>

import DropdownTimetracking from './DropdownTimetracking'
import DropdownNew from './DropdownNew'
import DropdownAccount from './DropdownAccount'
import DropdownNotifications from './DropdownNotifications'
import DropdownTasks from './DropdownTasks'
import DropdownMessages from './DropdownMessages'

export default {
  name: 'Header',
  components: {
    DropdownTimetracking,
    DropdownNew,
    DropdownAccount,
    DropdownNotifications,
    DropdownTasks,
    DropdownMessages
  },
  data() {
    return {
      children: [],
      pageTitle: ''
    }
  },
  methods: {
    toggleSidebar(){
      this.$root.$emit('toggle-minimize')
    }
  },
  watch: {
    $route: {
      handler: function(current) {
        if (current.meta && current.meta.links) {
          this.children = current.meta.links
        } else {
          this.children = []
        }
        if (current.meta && current.meta.linksRight) {
          this.childrenRight = current.meta.linksRight
        } else {
          this.childrenRight = []
        }
        if (current.meta && current.meta.dropdown) {
          this.dropdowns = current.meta.dropdown
        } else {
          this.dropdowns = []
        }
        if (current.meta && current.meta.pageTitle) {
          this.pageTitle = current.meta.pageTitle
        }else{
          this.pageTitle = ''
        }
      },
      immediate: true
    }
  }
}
</script>
