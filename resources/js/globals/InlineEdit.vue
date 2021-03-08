<template>
    <CTextarea
      v-if="editing && editType == 'textarea'"
      v-model="editValue"
      v-bind="$props"
    >
      <template #description>
        <div class="row">
          <div class="col-12 text-right">
            <CButton color="light" @click="cancelEditing"><CIcon name="cil-x"/></CButton>
            <CButton color="secondary" @click="saveEdit"><CIcon name="cil-check-alt"/></CButton>
          </div>
        </div>
      </template>
    </CTextarea>
    <CRow v-else-if="editing && editType == 'switch'">
      <CCol :col="`${ (label) ? 2 : 12 }`">
        <CSwitch class="mx-1" color="success" variant="3d" v-bind="$props" :checked.sync="editValue" />
      </CCol>
      <CCol col="10" v-if="label">
        <p v-html="label"></p>
      </CCol>
      <CCol col="12" class="text-right">
        <CButton color="light" @click="cancelEditing"><CIcon name="cil-x"/></CButton>
        <CButton color="secondary" @click="saveEdit"><CIcon name="cil-check-alt"/></CButton>
      </CCol>
    </CRow>
    <CRow v-else-if="editing && editType == 'wysiwyg'">
      <div class="col-12">
        <quill-editor v-model="editValue" :options="wysiwygOptions" v-bind="$props" />
      </div>
      <div class="col-12 text-right">
        <CButton color="light" @click="cancelEditing"><CIcon name="cil-x"/></CButton>
        <CButton color="secondary" @click="saveEdit"><CIcon name="cil-check-alt"/></CButton>
      </div>
    </CRow>
    <CRow v-else-if="editing && editType == 'vueselect'">
      <CCol col="12">
        <v-select
          v-model="editValue"
          v-bind="$props"
        >
          <template v-for="(index, name) in $scopedSlots" v-slot:[name]="data">
            <slot :name="name" v-bind="data"></slot>
          </template>
        </v-select>
      </CCol>
      <CCol col="12" class="text-right">
        <CButton color="light" @click="cancelEditing"><CIcon name="cil-x"/></CButton>
        <CButton color="secondary" @click="saveEdit"><CIcon name="cil-check-alt"/></CButton>
      </CCol>
    </CRow>
    <CInput
      v-else-if="editing && editType == 'date'"
      type="date"
      v-model="editValue"
      v-bind="$props"
    >
      <template #description>
        <div class="row">
          <div class="col-12 text-right">
            <CButton color="light" @click="cancelEditing"><CIcon name="cil-x"/></CButton>
            <CButton color="secondary" @click="saveEdit"><CIcon name="cil-check-alt"/></CButton>
          </div>
        </div>
      </template>
    </CInput>
    <CInput
      v-else-if="editing && (editType == 'input' || !editType)"
      v-model="editValue"
      v-bind="$props"
    >
      <template #description>
        <div class="row">
          <div class="col-12 text-right">
            <CButton color="light" @click="cancelEditing"><CIcon name="cil-x"/></CButton>
            <CButton color="secondary" @click="saveEdit"><CIcon name="cil-check-alt"/></CButton>
          </div>
        </div>
      </template>
    </CInput>
    <span v-else @click="enableEditing"><slot>{{ value }}</slot></span>
</template>

<script>

import Vue from 'vue'
import VueQuillEditor from 'vue-quill-editor'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
import vSelect from 'vue-select'
import 'vue-select/src/scss/vue-select.scss'

Vue.use(VueQuillEditor)

export default {
  name: 'InlineEdit',
  components: {
    vSelect
  },
  data () {
    return {
      tempValue: null,
      editValue: '',
      editing: false,
    }
  },
  props: {
    editType: {
      type: String
    },
    id: {
      type: String
    },
    label: {
      type: String
    },
    value: {
      type: [String, Object, Boolean, Array, Number]
    },
    size: {
      type: String
    },
    required: {
      type: Boolean,
      default: false
    },
    lazy: {
      type: Boolean,
      default: false
    },
    type: {
      type: String
    },
    prepend: {
      type: String
    },
    append: {
      type: String
    },
    variant: {
      type: String
    },
    options: {
      type: [Array, Object]
    },
    autoscroll: {
      type: Boolean,
      default: true
    },
    clearable: {
      type: Boolean,
      default: false
    }
  },
  watch: { 
    value: function(newVal, oldVal) {
      if(newVal !== oldVal){
        this.editValue = newVal;
        this.tempValue = null;
        this.editing = false;
      }
    }
  },
  computed: {
    wysiwygOptions(){
      var toolbarValues = (this.options) ? this.options : ['bold', 'italic', 'underline', 'strike'];
      return {
        modules: {
          toolbar: toolbarValues
        }
      }
    }
  },
  mounted() {
    this.editValue = this.value;
  },
  methods: {
    enableEditing: function(){
      this.tempValue = this.editValue;
      this.editing = true;
    },
    cancelEditing: function(){
      this.editValue = this.tempValue;
      this.tempValue = null;
      this.editing = false;
    },
    saveEdit: function(){
      this.$emit('input', this.editValue);
      this.editing = false;
    }
  }
}
</script>