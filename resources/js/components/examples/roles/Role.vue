<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard no-header>
        <CCardBody>
          <h3>Role id:  {{ $route.params.id }}</h3>

          <h4>
            Role name: {{ name }}
          </h4>

          <CButton color="primary" @click="goBack">Back</CButton>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Role',
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
      name: '',
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    }
  },
  mounted: function(){
    axios.get(  this.$apiAdress + '/coreui/roles/' + this.$route.params.id + '?token=' + localStorage.getItem("authtoken"))
    .then((response) => {
      this.name = response.data.name
    }).catch(() => {
      this.$router.push({ path: '/login' })
    });
  }
}


</script>
