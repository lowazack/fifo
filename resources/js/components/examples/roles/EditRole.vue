<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard>
        <CCardBody>
          <h3>
            Edit Role id: {{ $route.params.id }}
          </h3>
          <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
          >
            ({{ dismissCountDown }}) {{ message }}
          </CAlert>
          <CInput label="Name" type="text" placeholder="Name" v-model="role.name"/>
          <CButton color="primary" @click="update()">Save</CButton>
          <CButton color="primary" @click="goBack">Back</CButton>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'

export default {
  name: 'EditRole',
  /*
  props: {
    caption: {
      type: String,
      default: 'User id'
    },
  },
  */
  data: () => {
    return {
      role: {
        id: null,
        name: '',
      },
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
    update() {
      axios.post(this.$apiAdress + '/coreui/roles/' + this.$route.params.id + '?token=' + localStorage.getItem("authtoken"),
          {
            _method: 'PUT',
            name: this.role.name
          })
          .then(() => {
            this.message = 'Successfully updated role.';
            this.showAlert();
          }).catch((error) => {
        if (error.response.data.message == 'The given data was invalid.') {
          this.message = '';
          for (let key in error.response.data.errors) {
            if (error.response.data.errors.hasOwnProperty(key)) {
              this.message += error.response.data.errors[key][0] + '  ';
            }
          }
          this.showAlert();
        } else {
          this.$router.push({path: '/login'});
        }
      });
    },
    showAlert() {
      this.dismissCountDown = this.dismissSecs
    },
  },
  mounted: function () {
    let self = this;
    axios.get(this.$apiAdress + '/coreui/roles/' + self.$route.params.id + '/edit?token=' + localStorage.getItem("authtoken"))
        .then(function (response) {
          self.role = response.data;
        }).catch(function (error) {
      console.log(error);
      self.$router.push({path: '/login'});
    });
  }
}

/*
      items: (id) => {
        const user = usersData.find( user => user.id.toString() === id)
        const userDetails = user ? Object.entries(user) : [['id', 'Not found']]
        return userDetails.map(([key, value]) => {return {key: key, value: value}})
      },
*/

</script>
