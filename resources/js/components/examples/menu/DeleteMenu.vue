<template>
  <CRow>
    <CCol col="6" lg="6">
      <CCard>
        <CCardBody>
          <h4>Delete Menu</h4>
          <p>Are you sure?</p>
          <CAlert
              :show.sync="dismissCountDown"
              color="primary"
              fade
          >
            ({{ dismissCountDown }}) {{ message }}
          </CAlert>

          <CButton color="danger" @click="deleteMenu()">Delete</CButton>
          <CButton color="primary" @click="goBack">Back</CButton>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'

export default {
  name: 'DeleteMenu',
  data: () => {
    return {
      message: '',
      dismissSecs: 7,
      dismissCountDown: 0,
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
    deleteMenu() {
      axios.get(this.$apiAdress + '/coreui/menu/menu/delete?token=' + localStorage.getItem("authtoken") + '&id=' + this.$route.params.id, {})
          .then((response) => {
            if (response.data.success == true) {
              this.$router.go(-1)
            } else {
              this.message = "Can't delete. This menu have assigned menu elements";
              this.showAlert();
            }
          }).catch(() => {
        this.$router.push({path: '/login'});
      });
    },
    showAlert() {
      this.dismissCountDown = this.dismissSecs
    },
  },
  mounted: function () {
  }
}

</script>