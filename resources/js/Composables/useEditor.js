import Document from '@tiptap/extension-document'
import Div from '@/Extensions/TipTap/Div'
import Text from '@tiptap/extension-text'
import Link from '@tiptap/extension-link'
import emitter from "@/Services/emitter";

const useEditor = () => {
    const defaultExtensions = [
        Document,
        Div,
        Text,
        Link
    ]

    const insertEmoji = ({editorId, emoji}) => {
        if (emoji.hasOwnProperty('native')) { // We're making sure this is a real emoji
            emitter.emit('insertEmoji', {editorId, emoji});
        }
    }

    const focusEditor = ({editorId}) => {
        emitter.emit('focusEditor', {editorId});
    }

    const isDocEmpty = (text) => {
        if (text === '<div></div>') {
            return true;
        }

        return text === '';
    }

    return {
        defaultExtensions,
        insertEmoji,
        focusEditor,
        isDocEmpty
    }
}

export default useEditor;
