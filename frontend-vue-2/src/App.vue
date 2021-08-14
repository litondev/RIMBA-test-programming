<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3">
        <div class="collapse navbar-collapse">
    	   <ul class="navbar-nav mr-auto"
            style="z-index:10001">
        	   <li class="nav-item">                
    			     <a href="#" class="nav-link"
                        @click="$router.push('/')">
                        Customer
                    </a>
    		    </li>
                <li class="nav-item">
        	   		<a href="#" class="nav-link"
                        @click="$router.push('/item')">
                        Item
                    </a>
    		    </li>
                <li class="nav-item">
        			<a href="#" class="nav-link"
                        @click="$router.push('/sale')">
                        Sale
                    </a>
    		  </li>
    	   </ul>
        </div>
    </nav>

    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>