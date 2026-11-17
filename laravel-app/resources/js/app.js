import Alpine from 'alpinejs'
import './whatsapp.js'
import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import Link from '@tiptap/extension-link'
import Placeholder from '@tiptap/extension-placeholder'
import TextAlign from '@tiptap/extension-text-align'
import Image from '@tiptap/extension-image'
import { TextStyle, Color } from '@tiptap/extension-text-style'
import { Table, TableRow, TableCell, TableHeader } from '@tiptap/extension-table'
import Highlight from '@tiptap/extension-highlight'
import Subscript from '@tiptap/extension-subscript'
import Superscript from '@tiptap/extension-superscript'
import CharacterCount from '@tiptap/extension-character-count'
import Typography from '@tiptap/extension-typography'

document.addEventListener('alpine:init', () => {
    Alpine.data('richEditor', (initialValue = '') => {
        let editor = null

        return {
            content: initialValue,
            updatedAt: 0,
            fullscreen: false,
            showHeadingMenu: false,
            showTableMenu: false,
            charCount: 0,
            wordCount: 0,

            init() {
                const self = this

                editor = new Editor({
                    element: this.$refs.editorContent,
                    extensions: [
                        StarterKit,
                        Underline,
                        Link.configure({ openOnClick: false }),
                        Placeholder.configure({ placeholder: 'Írd be a leírást...' }),
                        TextAlign.configure({ types: ['heading', 'paragraph'] }),
                        Image,
                        TextStyle,
                        Color,
                        Table.configure({ resizable: true }),
                        TableRow,
                        TableCell,
                        TableHeader,
                        Highlight.configure({ multicolor: true }),
                        Subscript,
                        Superscript,
                        CharacterCount,
                        Typography,
                    ],
                    content: initialValue,
                    editorProps: {
                        attributes: {
                            class: 'prose prose-sm max-w-none min-h-[220px] px-4 py-3 focus:outline-none',
                        },
                    },
                    onUpdate({ editor: e }) {
                        self.content = e.getHTML()
                        self.updatedAt = Date.now()
                        self.charCount = e.storage.characterCount.characters()
                        self.wordCount = e.storage.characterCount.words()
                    },
                    onSelectionUpdate() { self.updatedAt = Date.now() },
                    onTransaction()     { self.updatedAt = Date.now() },
                })
            },

            destroy() { editor?.destroy() },

            isActive(type, opts = null) {
                if (!editor || this.updatedAt < 0) return false
                return opts !== null ? editor.isActive(type, opts) : editor.isActive(type)
            },

            alignActive(dir) {
                return this.updatedAt >= 0 && (editor?.isActive({ textAlign: dir }) ?? false)
            },

            run(cmd) {
                editor?.chain().focus()[cmd]().run()
            },

            align(dir) {
                editor?.chain().focus().setTextAlign(dir).run()
            },

            currentBlock() {
                if (!editor || this.updatedAt < 0) return 'Bekezdés'
                for (const level of [1, 2, 3, 4]) {
                    if (editor.isActive('heading', { level })) return `Cím ${level}`
                }
                return 'Bekezdés'
            },

            setHeading(level) {
                this.showHeadingMenu = false
                if (level === 0) {
                    editor?.chain().focus().setParagraph().run()
                } else {
                    editor?.chain().focus().toggleHeading({ level }).run()
                }
            },

            setTextColor(color) {
                editor?.chain().focus().setColor(color).run()
            },

            setHighlight(color) {
                editor?.chain().focus().setHighlight({ color }).run()
            },

            setLink() {
                const url = window.prompt('URL:')
                if (!url) return
                editor?.chain().focus().setLink({ href: url, target: '_blank' }).run()
            },

            insertImage() {
                const url = window.prompt('Kép URL:')
                if (!url) return
                editor?.chain().focus().setImage({ src: url }).run()
            },

            insertTable() {
                this.showTableMenu = false
                editor?.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()
            },

            tableCmd(cmd) {
                this.showTableMenu = false
                editor?.chain().focus()[cmd]().run()
            },

            inTable() {
                return this.updatedAt >= 0 && (editor?.isActive('table') ?? false)
            },
        }
    })
})

window.Alpine = Alpine
Alpine.start()
