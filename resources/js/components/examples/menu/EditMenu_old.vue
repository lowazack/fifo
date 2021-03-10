<template>
  <CRow>
    <CCol col="12" xl="6">
      <transition name="slide">
        <CCard>
          <CCardBody>
            <div v-if="!isRoleSelected">
              <CSelect
                  :value.sync="roleSelected"
                  :options="roles"
                  label="Select role assigned to menu"
              />
              <CButton color="primary" @click="selectRole()">Edit</CButton>
            </div>
            <div v-else>
              <CDataTable
                  hover
                  :items="tableData"
                  :fields="fields"
              >
                <template #assigned="{item}">
                  <td v-if="item.assigned">
                    <CButton color="primary" @click="toggleElement( item.id )">Hide</CButton>
                  </td>
                  <td v-else>
                    <CButton color="danger" @click="toggleElement( item.id )">Show</CButton>
                  </td>
                </template>
                <template #dropdown="{item}">
                  <td>
                    <i v-if="item.dropdown" class="cui-chevron-right icons font-2xl d-block"></i>
                  </td>
                </template>
              </CDataTable>
            </div>
          </CCardBody>
        </CCard>
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'

export default {
  name: 'TheSidebar',
  data() {
    return {
      isRoleSelected: false,
      roleSelected: 'guest',
      roles: [],
      buffer: [],
      tableData: [],
      fields: ['assigned', 'dropdown', 'slug', 'name'],
      thisMenuRole: null,
    }
  },
  methods: {
    addElementToBuffer(data, icon) {
      this.buffer.push(
          {
            dropdown: icon,
            name: data['name'],
            id: data['id'],
            slug: data['slug'],
            assigned: data['assigned'],
          }
      );
    },
    innerBuildArrayData(data, deep) {
      for (let i = 0; i < data.length; i++) {
        switch (data[i]['slug']) {
          case 'link':
            if (deep > 1) {
              this.addElementToBuffer(data[i], true);
            } else {
              this.addElementToBuffer(data[i], false);
            }
            break
          case 'title':
            this.addElementToBuffer(data[i], false);
            break;
          case 'dropdown':
            this.addElementToBuffer(data[i], false);
            this.innerBuildArrayData(data[i]['elements'], deep + 1)
            break;
        }
      }
    },
    buildArrayData(data) {
      this.buffer = [];
      this.innerBuildArrayData(data, 1);
      return this.buffer;
    },
    selectRole() {
      axios.get(this.$apiAdress + '/coreui/menu/edit/selected?token=' + localStorage.getItem("authtoken") + '&role=' + this.roleSelected)
          .then((response) => {
            this.tableData = this.buildArrayData(response.data.menuToEdit);
            this.thisMenuRole = response.data.role;
            this.isRoleSelected = true;
          }).catch(() => {
        this.$router.push({path: '/login'});
      });
    },
    toggleElement(id) {
      let self = this;
      axios.get(this.$apiAdress + '/coreui/menu/edit/selected/switch?token=' + localStorage.getItem("authtoken") + '&role=' + this.thisMenuRole + '&id=' + id)
          .then(function (response) {
            self.selectRole();
          }).catch(function (error) {
        console.log(error);
        self.$router.push({path: '/login'});
      });
    }
  },
  mounted() {
    this.$root.$on('toggle-sidebar', () => this.show = !this.show)
    let self = this;
    axios.get(this.$apiAdress + '/coreui/menu/edit?token=' + localStorage.getItem("authtoken"))
        .then(function (response) {
          self.roles = response.data.roles;
        }).catch(function (error) {
      console.log(error);
      self.$router.push({path: '/login'});
    });
  }
}
</script>
