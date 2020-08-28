<template>
    <div class="uk-position-cover uk-overlay uk-overlay-default uk-flex uk-flex-center uk-flex-middle">
        <div>
            <slot>Loading... <span uk-spinner="ratio 0.7"></span></slot>
        </div>
    </div>
</template>