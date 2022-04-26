export const nullableString = (element) => {
    if(!! element){
        return element;
    }
    
    return '<span class="text-gray-400">N/A</span>';
}
