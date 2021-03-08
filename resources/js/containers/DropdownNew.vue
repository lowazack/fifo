<template>
  <CDropdown 
    placement="bottom"
    :caret="false"
    in-nav
    class="c-header-nav-item mx-2"
    add-menu-classes="p-0 rounded"
  >
    <template #toggler>
      <CHeaderNavLink class="p-2 rounded">
        <CIcon class="m-0" name="cil-plus-circle"/>
      </CHeaderNavLink>
    </template>
    <CDropdownHeader 
      tag="div" 
      class="text-center bg-light"
    >
      <strong>Add new</strong>
    </CDropdownHeader>
    <CDropdownItem class="p-2" @click="openTaskSidebar()">
      <CIcon name="cil-task" class="text-success"/> Task
    </CDropdownItem>
    <CDropdownItem class="p-2">
      <CIcon name="cil-building" class="text-danger"/> Client
    </CDropdownItem>
    <CDropdownItem class="p-2">
      <CIcon name="cil-library" class="text-info"/> Job
    </CDropdownItem>
  </CDropdown>
</template>
<script>
export default {
  name: 'DropdownNew',
  data () {
    return { itemsCount: 5 }
  },
  methods: {
    openTaskSidebar() {
      this.$root.$emit('aside', 'TaskSidebar', {});
    }
  }
}
</script>

<style scoped>
  .c-icon {
    margin-right: 0.5rem;
  }
</style>