<template>
  <CSidebar
    fixed
    :minimize="minimize"
    unfoldable
    :show="show"
    @update:show="(value) => $store.commit('set', ['sidebarShow', value])"
  >
    <CSidebarBrand v-if="!minimize" to="/">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72.77 35.76" id="logo"><g><path fill="#FFF" d="M26.21,11.09V35.23H20.4v-20h-11v20H3.65v-20H0V11.09H3.65V8A7.25,7.25,0,0,1,6.24,2.21,10.17,10.17,0,0,1,13.06,0,13.34,13.34,0,0,1,20.5,2.26L18.58,6.1a9.63,9.63,0,0,0-4.47-1.4,5.1,5.1,0,0,0-3.48,1.16A3.86,3.86,0,0,0,9.31,8.93v2.16Z"/><path fill="#FFF" d="M42.43,0a13.3,13.3,0,0,1,7.44,2.26L48,6.1a10.05,10.05,0,0,0-4.46-1.4,4.39,4.39,0,0,0-3.17,1.16,4,4,0,0,0-1.2,3.07v2.16H45.7v4.17H39.22v20H33.46v-20H29.81V11.09h3.65V8a7.41,7.41,0,0,1,2.47-5.81A9.4,9.4,0,0,1,42.43,0Z"/><path fill="#FFF" d="M50,14a13.08,13.08,0,0,1,9.45-3.65A13.18,13.18,0,0,1,69,14,12,12,0,0,1,72.77,23,12,12,0,0,1,69,32.11a13.18,13.18,0,0,1-9.48,3.65A13.14,13.14,0,0,1,50,32.14,12.11,12.11,0,0,1,46.22,23,12.06,12.06,0,0,1,50,14Zm14.9,3.45a7.61,7.61,0,0,0-10.9,0,7.84,7.84,0,0,0-2.23,5.67A7.67,7.67,0,0,0,54,28.68a7.65,7.65,0,0,0,10.9,0,7.67,7.67,0,0,0,2.23-5.59A7.84,7.84,0,0,0,64.92,17.42Z"/></g></svg>
    </CSidebarBrand>
    <CSidebarBrand v-if="minimize" to="/">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1022 1022" id="logo">
        <g id="Ellipse_1" data-name="Ellipse 1" class="cls-1">
          <circle cx="511" cy="511" r="511"/>
          <path fill="#FFF" d="M714.49,391.6V796.31H617.13V461.6H432.88V796.31H336.33V461.6H275.18v-70h61.15V340.11q0-60.35,43.45-97.36t114.25-37q66.78,0,124.71,37.82l-32.18,64.36q-42.66-23.32-74.83-23.33-36.21,0-58.33,19.31t-22.13,51.5v36.2Z"/>
        </g>
      </svg>
    </CSidebarBrand>
    <CRenderFunction flat :content-to-render="nav"/>
    <CSidebarFooter class="p-0">
      <CSidebarNav>
        <CSidebarNavItem to="/settings" icon="cil-settings" name="Settings" />
      </CSidebarNav>
    </CSidebarFooter>
  </CSidebar>
</template>

<script>
import axios from 'axios'
import navigation from './_nav'
import navigationExample from './_navExample'
export default {
  name: 'Sidebar',
  data () {
    return {
      nav: navigation,
      menu: true,
      buffer: [],
    }
  },
  computed: {
    show () {
      return this.$store.state.sidebarShow
    },
    minimize () {
      return this.$store.state.sidebarMinimize
    }
  },
  methods: {
    switchMenu(){
      if(this.menu){
        this.nav = navigation;
      }else{
        this.nav = navigationExample;
      }
    },
    dropdown(data){
      let result = {
            _name: 'CSidebarNavDropdown',
            name: data['name'],
            route: data['href'],
            icon: data['icon'],
            _children: [],
      }
      for(let i=0; i<data['elements'].length; i++){
        if(data['elements'][i]['slug'] == 'dropdown'){
          result._children.push( this.dropdown(data['elements'][i]) );
        }else{
          result._children.push(
            {
                   _name:  'CSidebarNavItem',
                   name:   data['elements'][i]['name'],
                   to:     data['elements'][i]['href'],
                   icon:   data['elements'][i]['icon']
            }
          );
        }
      }
      return result;
    },
    rebuildData(data){
      this.buffer = [{
        _name: 'CSidebarNav',
        _children: []
      }];
      for(let k=0; k<data.length; k++){
        switch(data[k]['slug']){
          case 'link':
            if(data[k]['href'].indexOf('http') !== -1){
              this.buffer[0]._children.push(
                  {
                      _name: 'CSidebarNavItem',
                      name: data[k]['name'],
                      href: data[k]['href'],
                      icon: data[k]['icon'],
                      target: '_blank'
                  }
              );
            }else{
              this.buffer[0]._children.push(
                  {
                      _name: 'CSidebarNavItem',
                      name: data[k]['name'],
                      to:   data[k]['href'],
                      icon: data[k]['icon'],
                  }
              );
            }
          break;
          case 'title':
            this.buffer[0]._children.push(
              {
                _name: 'CSidebarNavTitle',
                _children: [data[k]['name']]
              }
            );
          break;
          case 'dropdown':
            this.buffer[0]._children.push( this.dropdown(data[k]) );
          break;
        }
      }
      return this.buffer;
    }
  },
  mounted () {
    this.$root.$on('toggle-minimize', () => {
      this.$store.commit('toggle', 'sidebarMinimize')
    })
    this.$root.$on('toggle-sidebar', () => {
      const sidebarOpened = this.show === true || this.show === 'responsive'
      this.show = sidebarOpened ? false : 'responsive'
    })
    this.$root.$on('toggle-sidebar-mobile', () => {
      const sidebarClosed = this.show === 'responsive' || this.show === false
      this.show = sidebarClosed ? true : 'responsive'
    })
    let self = this;


    /*axios.get( this.$apiAdress + '/coreui/menu?token=' + localStorage.getItem("authtoken") )
    .then(function (response) {
      self.nav = self.rebuildData(response.data);
    }).catch(function (error) {
      console.log(error);
      self.$router.push({ path: '/login' });
    });*/
    //self.nav = self.rebuildData(navigation);
  }
}
</script>
