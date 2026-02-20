import parse from 'html-react-parser'

export function parseHTML(html: string) {
  return parse(html)
}
