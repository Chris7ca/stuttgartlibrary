<template>
    <tbody>
        <tr v-for="(row, rI) in rows" :key="rI">
            <td v-for="(column, cI) in columns" :key="cI">
                <content-placeholders>
                    <content-placeholders-text :lines="1" />
                </content-placeholders>
            </td>
        </tr>
    </tbody>
</template>

<script>
export default {
    props: ['rows', 'columns']
}
</script>