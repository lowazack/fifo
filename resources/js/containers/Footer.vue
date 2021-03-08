<template>
  <CFooter :fixed="false">
    <div>
      <span class="ml-1">&copy; {{new Date().getFullYear()}} Fifo</span>
    </div>
    <div class="ml-auto">
      <span class="mr-1">Built with ❤️ by</span>
      <a href="https://www.many.co.uk" target="_blank">Many</a>
    </div>
  </CFooter>
</template>

<script>
export default {
  name: 'Footer'
}
</script>
